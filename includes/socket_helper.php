<script src="https://cdn.socket.io/4.7.4/socket.io.min.js"></script> <!-- Use latest version -->
<script>
    // const socket = io('http://10.0.0.121:3000');
    const socket = io(
        window.location.hostname === 'localhost'
            ? 'http://10.0.0.121:3000'
            : 'https://online.indotaichen.com',
        { path: '/socket.io/' }
    );
    socket.onAny((event, ...args) => {
        console.log("EVENT:", event, args);
    });

    var tagss = [];
    let epcTable;
    let globalRoomId
    let hasSubscribed = false;

    function subscribe(roomId) {
        socket.emit('subscribe', {
            roomId
        });
        addMessage(`Subscribed to room: ${roomId}`);
        globalRoomId = roomId
    }

    function unsubscribe(roomId) {
        socket.emit('unsubscribe', {
            roomId
        });
        addMessage(`Unsubscribed from room: ${roomId}`);
    }

    function addMessage(msg) {
        console.log(msg);
    }

    // Convert hex string to Document ID (ASCII)
    function getDocID(hex) {
        let ascii = '';
        for (let i = 0; i < hex.length; i += 2) {
            const code = parseInt(hex.substr(i, 2), 16);
            if (!isNaN(code)) {
                const char = String.fromCharCode(code);
                if (/^[A-Za-z0-9]$/.test(char)) {
                    ascii += char;
                }
            }
        }
        return ascii;
    }

    function popupModal() {
        $('#modalRFID').modal('show');
    }

    function popoutModal() {
        $('#modalRFID').modal('hide');
    }

    function countdownButtonRFID() {
        let countdown = 5; // detik
        const btn = document.getElementById("submitBtnRFID");

        const interval = setInterval(() => {
            btn.textContent = `Submit (${countdown})`;
            btn.disabled = true;

            countdown--;

            if (countdown < 0) {
                clearInterval(interval);
                btn.textContent = "Submit";
                btn.disabled = false;
            }
        }, 1000);
    }

    function globalProcessOnListenSocket(options = {}) {
        const { roomId, tags, epcTable, filteredData, globalData } = options
        
        tagss = [...tags]
        filteredData.splice(0, filteredData.length); // ✅ clear tapi referensi sama

        // 🧹 Hapus semua row dulu
        if (epcTable) {
            epcTable.clear().draw();
        }

        let index = 1;

        for (const hexTag of tagss) {
            let docId = getDocID(hexTag);
            console.log("Decoded DOC ID:", docId);

            // ✅ Jika diawali "DR"
            if (docId.startsWith("DR") && docId.length > 2) {
                // sisipkan "-" sebelum char terakhir
                docId = docId.slice(0, -1) + "-" + docId.slice(-1);
            }

            // Check if existing on current filteredData
            const existsOnfilteredData = filteredData.some(
                item => item.no_resep.trim() === docId.trim()
            );

            if (existsOnfilteredData) {
                addMessage(`SUCCESS_SUBSCRIBE: Already on array ${hexTag}`);
                continue;
            }

            // ✅ dynamic checker
            let relatedData = null;
            if (options.checkFn) {
                // kalau dikasih custom function, pakai itu
                relatedData = globalData.find(item => options.checkFn(item, docId));
            } else if (options.status) {
                // default check by status
                relatedData = globalData.find(item =>
                    item.no_resep.trim() == docId.trim() &&
                    item.status === options.status
                );
            } else {
                // fallback: hanya match docId
                relatedData = globalData.find(item => item.no_resep.trim() == docId.trim());
            }


            if (!relatedData) {
                addMessage(`SUCCESS_SUBSCRIBE: No valid data for EPC ${hexTag}`);
                continue;
            }

            // Push ke array hasil
            filteredData.push(relatedData);

            // Tambah ke DataTable
            if (epcTable) {
               // ✅ dynamic column mapping
                const rowValues = (options.columns || []).map(col => {
                    if (typeof col === "function") {
                        return col(relatedData, index, docId); // custom renderer
                    } else if (typeof col === "string") {
                        return relatedData?.[col] ?? ""; // ambil property langsung
                    } else {
                        return "";
                    }
                });

                const newRow = epcTable.row.add(rowValues).node();
                newRow.id = `row-${relatedData?.no_resep.trim()}`;
            }

            index++;
        }

        // Draw ulang DataTable setelah semua row ditambah
        if (epcTable) {
            epcTable.draw(false);
        }

        if (filteredData.length > 0) {
            // ✅ Ada data valid → show table & popup
            $('#epcTable').show();
            // countdownButtonRFID();
            popupModal();
        } else {
            // ❌ Tidak ada data valid → hide table & close modal
            $('#epcTable').hide();
            $('#modalRFID').modal('hide');
        }

        addMessage(`SUCCESS_SUBSCRIBE: ${JSON.stringify({ roomId, tags })}`);
    }

    function globalProcessOnListenSocketForIddle(options = {}) {
        let { roomId, tags, epcTable, filteredData, deletedDRData, globalData } = options
        
        tagss = [...tags];
        filteredData.splice(0, filteredData.length); // ✅ clear tapi referensi sama

        // 🧹 Hapus semua row dulu
        if (epcTable) {
            epcTable.clear().draw();
        }

        let index = 1;

        for (const hexTag of tagss) {
            let docId = getDocID(hexTag);
            console.log("Decoded DOC ID:", docId);

            // ✅ Jika diawali "DR"
            if (docId.startsWith("DR") && docId.length > 2) {
                // sisipkan "-" sebelum char terakhir
                docId = docId.slice(0, -1) + "-" + docId.slice(-1);

                // ✅ Cek apakah sudah ada di array deletedDR
                const exists = deletedDRData.some(
                    item => item.trim() === docId.trim()
                );

                if (exists) {
                    continue; // lompat ke iterasi selanjutnya
                }
            }

            // Check if existing on current filteredData
            const existsOnfilteredData = filteredData.some(
                item => item.no_resep.trim() === docId.trim()
            );

            if (existsOnfilteredData) {
                addMessage(`SUCCESS_SUBSCRIBE: Already on array ${hexTag}`);
                continue;
            }

            // ✅ dynamic checker
            let relatedData = null;
            if (options.checkFn) {
                // kalau dikasih custom function, pakai itu
                relatedData = globalData.find(item => options.checkFn(item, docId));
            } else if (options.status) {
                // default check by status
                relatedData = globalData.find(item =>
                    item.no_resep.trim() == docId.trim() &&
                    item.status === options.status
                );
            } else {
                // fallback: hanya match docId
                relatedData = globalData.find(item => item.no_resep.trim() == docId.trim());
            }

            if (!relatedData) {
                addMessage(`SUCCESS_SUBSCRIBE: No valid data for EPC ${hexTag}`);
                continue;
            }

            // Push ke array hasil
            filteredData.push(relatedData);

            // Tambah ke DataTable
            if (epcTable) {
                // ✅ dynamic column mapping
                const rowValues = (options.columns || []).map(col => {
                    if (typeof col === "function") {
                        return col(relatedData, index, docId); // custom renderer
                    } else if (typeof col === "string") {
                        return relatedData?.[col] ?? ""; // ambil property langsung
                    } else {
                        return "";
                    }
                });

                const newRow = epcTable.row.add(rowValues).node();
                newRow.id = `row-${relatedData?.no_resep.trim()}`;
            }

            index++;
        }

        // Draw ulang DataTable setelah semua row ditambah
        if (epcTable) {
            epcTable.draw(false);
        }

        if (filteredData.length > 0) {
            // ✅ Ada data valid → show table & popup
            $('#epcTable').show();
            // countdownButtonRFID();
            popupModal();
        } else {
            // ❌ Tidak ada data valid → hide table & close modal
            $('#epcTable').hide();
            $('#modalRFID').modal('hide');
        }
        addMessage(`SUCCESS_SUBSCRIBE: ${JSON.stringify({ roomId, tags })}`);
    }
</script>