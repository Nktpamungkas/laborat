<?php
    $hostname = "10.0.0.21";
                             // $database = "NOWTEST"; // SERVER NOW 20
    $database    = "NOWPRD"; // SERVER NOW 22
    $user        = "db2admin";
    $passworddb2 = "Sunkam@24809";
    $port        = "25000";
    $conn_string = "DRIVER={IBM ODBC DB2 DRIVER}; HOSTNAME=$hostname; PORT=$port; PROTOCOL=TCPIP; UID=$user; PWD=$passworddb2; DATABASE=$database;";
    $conn1       = db2_connect($conn_string, '', '');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
    <title>Kartu Riwayat</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="generator" content="PhpSpreadsheet, https://github.com/PHPOffice/PhpSpreadsheet">
    <meta name="author" content="W-DIT-000187" />
    <style type="text/css">
        html {
            font-family: Calibri, Arial, Helvetica, sans-serif;
            font-size: 11pt;
            background-color: white
        }

        a.comment-indicator:hover+div.comment {
            background: #ffd;
            position: absolute;
            display: block;
            border: 1px solid black;
            padding: 0.5em
        }

        a.comment-indicator {
            background: red;
            display: inline-block;
            border: 1px solid black;
            width: 0.5em;
            height: 0.5em
        }

        div.comment {
            display: none
        }

        table {
            border-collapse: collapse;
            page-break-after: always
        }

        .gridlines td {
            border: 1px dotted black
        }

        .gridlines th {
            border: 1px dotted black
        }

        .b {
            text-align: center
        }

        .e {
            text-align: center
        }

        .f {
            text-align: right
        }

        .inlineStr {
            text-align: left
        }

        .n {
            text-align: right
        }

        .s {
            text-align: left
        }

        td.style0 {
            vertical-align: bottom;
            border-bottom: none #000000;
            border-top: none #000000;
            border-left: none #000000;
            border-right: none #000000;
            color: #000000;
            font-family: 'Calibri';
            font-size: 11pt;
            background-color: white
        }

        th.style0 {
            vertical-align: bottom;
            border-bottom: none #000000;
            border-top: none #000000;
            border-left: none #000000;
            border-right: none #000000;
            color: #000000;
            font-family: 'Calibri';
            font-size: 11pt;
            background-color: white
        }

        td.style1 {
            vertical-align: middle;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        th.style1 {
            vertical-align: middle;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        td.style2 {
            vertical-align: middle;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 2px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        th.style2 {
            vertical-align: middle;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 2px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        td.style3 {
            vertical-align: middle;
            border-bottom: 2px solid #000000 !important;
            border-top: 1px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        th.style3 {
            vertical-align: middle;
            border-bottom: 2px solid #000000 !important;
            border-top: 1px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        td.style4 {
            vertical-align: middle;
            border-bottom: 2px solid #000000 !important;
            border-top: 1px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 2px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        th.style4 {
            vertical-align: middle;
            border-bottom: 2px solid #000000 !important;
            border-top: 1px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 2px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        td.style5 {
            vertical-align: bottom;
            text-align: center;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 2px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 11pt;
            background-color: white
        }

        th.style5 {
            vertical-align: bottom;
            text-align: center;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 2px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 11pt;
            background-color: white
        }

        td.style6 {
            vertical-align: bottom;
            text-align: center;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 11pt;
            background-color: white
        }

        th.style6 {
            vertical-align: bottom;
            text-align: center;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 11pt;
            background-color: white
        }

        td.style7 {
            vertical-align: middle;
            text-align: center;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 14pt;
            background-color: white
        }

        th.style7 {
            vertical-align: middle;
            text-align: center;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 14pt;
            background-color: white
        }

        td.style8 {
            vertical-align: bottom;
            text-align: center;
            border-bottom: 2px solid #000000 !important;
            border-top: 1px solid #000000 !important;
            border-left: 2px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 11pt;
            background-color: white
        }

        th.style8 {
            vertical-align: bottom;
            text-align: center;
            border-bottom: 2px solid #000000 !important;
            border-top: 1px solid #000000 !important;
            border-left: 2px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 11pt;
            background-color: white
        }

        td.style9 {
            vertical-align: bottom;
            text-align: center;
            border-bottom: 2px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 2px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 11pt;
            background-color: white
        }

        th.style9 {
            vertical-align: bottom;
            text-align: center;
            border-bottom: 2px solid #000000 !important;
            border-top: 1px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 11pt;
            background-color: white
        }

        td.style10 {
            vertical-align: middle;
            text-align: center;
            border-bottom: 2px solid #000000 !important;
            border-top: 1px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 14pt;
            background-color: white
        }

        th.style10 {
            vertical-align: middle;
            text-align: center;
            border-bottom: 2px solid #000000 !important;
            border-top: 1px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 14pt;
            background-color: white
        }

        td.style11 {
            vertical-align: middle;
            text-align: center;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: none #000000;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 14pt;
            background-color: white
        }

        th.style11 {
            vertical-align: middle;
            text-align: center;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: none #000000;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 14pt;
            background-color: white
        }

        td.style12 {
            vertical-align: middle;
            text-align: center;
            border-bottom: 2px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: none #000000;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 14pt;
            background-color: white
        }

        th.style12 {
            vertical-align: middle;
            text-align: center;
            border-bottom: 2px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: none #000000;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 14pt;
            background-color: white
        }

        td.style13 {
            vertical-align: bottom;
            text-align: left;
            padding-left: 0px;
            border-bottom: none #000000;
            border-top: 2px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: none #000000;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        th.style13 {
            vertical-align: bottom;
            text-align: left;
            padding-left: 0px;
            border-bottom: none #000000;
            border-top: 2px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: none #000000;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        td.style14 {
            vertical-align: bottom;
            text-align: left;
            padding-left: 0px;
            border-bottom: none #000000;
            border-top: 2px solid #000000 !important;
            border-left: none #000000;
            border-right: 2px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        th.style14 {
            vertical-align: bottom;
            text-align: left;
            padding-left: 0px;
            border-bottom: none #000000;
            border-top: 2px solid #000000 !important;
            border-left: none #000000;
            border-right: 2px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        td.style15 {
            vertical-align: top;
            text-align: left;
            padding-left: 0px;
            border-bottom: 2px solid #000000 !important;
            border-top: none #000000;
            border-left: 1px solid #000000 !important;
            border-right: none #000000;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        th.style15 {
            vertical-align: top;
            text-align: left;
            padding-left: 0px;
            border-bottom: 2px solid #000000 !important;
            border-top: none #000000;
            border-left: 1px solid #000000 !important;
            border-right: none #000000;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        td.style16 {
            vertical-align: top;
            text-align: left;
            padding-left: 0px;
            border-bottom: 2px solid #000000 !important;
            border-top: none #000000;
            border-left: none #000000;
            border-right: 2px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        th.style16 {
            vertical-align: top;
            text-align: left;
            padding-left: 0px;
            border-bottom: 2px solid #000000 !important;
            border-top: none #000000;
            border-left: none #000000;
            border-right: 2px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        td.style17 {
            vertical-align: bottom;
            text-align: center;
            border-bottom: none #000000;
            border-top: none #000000;
            border-left: none #000000;
            border-right: none #000000;
            color: #000000;
            font-family: 'Arial';
            font-size: 11pt;
            background-color: white
        }

        th.style17 {
            vertical-align: bottom;
            text-align: center;
            border-bottom: none #000000;
            border-top: none #000000;
            border-left: none #000000;
            border-right: none #000000;
            color: #000000;
            font-family: 'Arial';
            font-size: 11pt;
            background-color: white
        }

        td.style18 {
            vertical-align: bottom;
            border-bottom: none #000000;
            border-top: none #000000;
            border-left: none #000000;
            border-right: none #000000;
            color: #000000;
            font-family: 'Arial';
            font-size: 11pt;
            background-color: white
        }

        th.style18 {
            vertical-align: bottom;
            border-bottom: none #000000;
            border-top: none #000000;
            border-left: none #000000;
            border-right: none #000000;
            color: #000000;
            font-family: 'Arial';
            font-size: 11pt;
            background-color: white
        }

        td.style19 {
            vertical-align: bottom;
            border-bottom: none #000000;
            border-top: none #000000;
            border-left: none #000000;
            border-right: none #000000;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        th.style19 {
            vertical-align: bottom;
            border-bottom: none #000000;
            border-top: none #000000;
            border-left: none #000000;
            border-right: none #000000;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        td.style20 {
            vertical-align: bottom;
            text-align: center;
            border-bottom: none #000000;
            border-top: none #000000;
            border-left: none #000000;
            border-right: none #000000;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        th.style20 {
            vertical-align: bottom;
            text-align: center;
            border-bottom: none #000000;
            border-top: none #000000;
            border-left: none #000000;
            border-right: none #000000;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        td.style21 {
            vertical-align: middle;
            text-align: center;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 2px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        th.style21 {
            vertical-align: middle;
            text-align: center;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 2px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        td.style22 {
            vertical-align: middle;
            text-align: center;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        th.style22 {
            vertical-align: middle;
            text-align: center;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        td.style23 {
            vertical-align: bottom;
            text-align: center;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        th.style23 {
            vertical-align: bottom;
            text-align: center;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        td.style24 {
            vertical-align: bottom;
            text-align: center;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 2px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        th.style24 {
            vertical-align: bottom;
            text-align: center;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 2px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        td.style25 {
            vertical-align: middle;
            text-align: center;
            border-bottom: 2px solid #000000 !important;
            border-top: 1px solid #000000 !important;
            border-left: 2px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        th.style25 {
            vertical-align: middle;
            text-align: center;
            border-bottom: 2px solid #000000 !important;
            border-top: 1px solid #000000 !important;
            border-left: 2px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        td.style26 {
            vertical-align: middle;
            text-align: left;
            padding-left: 0px;
            border-bottom: 2px solid #000000 !important;
            border-top: 1px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        th.style26 {
            vertical-align: middle;
            text-align: left;
            padding-left: 0px;
            border-bottom: 2px solid #000000 !important;
            border-top: 1px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        td.style27 {
            vertical-align: middle;
            text-align: left;
            padding-left: 0px;
            border-bottom: 2px solid #000000 !important;
            border-top: 1px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        th.style27 {
            vertical-align: middle;
            text-align: left;
            padding-left: 0px;
            border-bottom: 2px solid #000000 !important;
            border-top: 1px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        td.style28 {
            vertical-align: middle;
            text-align: left;
            padding-left: 0px;
            border-bottom: 2px solid #000000 !important;
            border-top: 1px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 2px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        th.style28 {
            vertical-align: middle;
            text-align: left;
            padding-left: 0px;
            border-bottom: 2px solid #000000 !important;
            border-top: 1px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 2px solid #000000 !important;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        td.style29 {
            vertical-align: middle;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 2px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        th.style29 {
            vertical-align: middle;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 2px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        td.style30 {
            vertical-align: middle;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        th.style30 {
            vertical-align: middle;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        td.style31 {
            vertical-align: middle;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        th.style31 {
            vertical-align: middle;
            border-bottom: 1px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        td.style32 {
            vertical-align: middle;
            border-bottom: 2px solid #000000 !important;
            border-top: 1px solid #000000 !important;
            border-left: 2px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        th.style32 {
            vertical-align: middle;
            border-bottom: 2px solid #000000 !important;
            border-top: 1px solid #000000 !important;
            border-left: 2px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        td.style33 {
            vertical-align: middle;
            border-bottom: 2px solid #000000 !important;
            border-top: 1px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        th.style33 {
            vertical-align: middle;
            border-bottom: 2px solid #000000 !important;
            border-top: 1px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        td.style34 {
            vertical-align: middle;
            border-bottom: 2px solid #000000 !important;
            border-top: 1px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        th.style34 {
            vertical-align: middle;
            border-bottom: 2px solid #000000 !important;
            border-top: 1px solid #000000 !important;
            border-left: 1px solid #000000 !important;
            border-right: 1px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            background-color: white
        }

        table.sheet0 col.col0 {
            width: 29.14444411pt
        }

        table.sheet0 col.col1 {
            width: 48.12222167pt
        }

        table.sheet0 col.col2 {
            width: 60.9999993pt
        }

        table.sheet0 col.col3 {
            width: 58.28888822pt
        }

        table.sheet0 col.col4 {
            width: 112.51110982pt
        }

        table.sheet0 col.col5 {
            width: 47.4444439pt
        }

        table.sheet0 col.col6 {
            width: 64.38888815pt
        }

        table.sheet0 col.col7 {
            width: 43.37777728pt
        }

        table.sheet0 tr {
            height: 15pt
        }

        table.sheet0 tr.row0 {
            height: 25.5pt
        }

        table.sheet0 tr.row1 {
            height: 26.25pt
        }

        table.sheet0 tr.row2 {
            height: 15pt
        }

        table.sheet0 tr.row4 {
            height: 15pt
        }

        table.sheet0 tr.row5 {
            height: 15pt
        }

        table.sheet0 tr.row7 {
            height: 15pt
        }
    </style>
</head>

<body onload="print();">
    <table border="0" cellpadding="0" cellspacing="0" id="sheet0" class="sheet0 gridlines" width="100%">
        <col class="col0">
        <col class="col1">
        <col class="col2">
        <col class="col3">
        <col class="col4">
        <col class="col5">
        <col class="col6">
        <col class="col7">
        <tbody>
            <?php
                $no_mesin = isset($_GET['kode']) ? $_GET['kode'] : '';
                ini_set("error_reporting", 0);

                $query_breakdown = "SELECT
                                        p3.CODE AS WORKORDERCODE,
                                        p.PMBOMCODE AS NO_MESIN,
                                        p2.SHORTDESCRIPTION AS MESIN,
                                        p2.GENERICDATA2 AS TYPE,
                                        d.SHORTDESCRIPTION AS DOCUMENT,
                                        p.IDENTIFIEDDATE AS TANGGAL,
                                        p3.REMARKS AS KEGIATAN,
                                        p4.PRODUCTSHORTDESC || ' ( ' || floor(p4.ACTUALQUANITY) || ' ' || u.LONGDESCRIPTION  || ')' AS SPAREPARTS
                                    FROM
                                        PMBREAKDOWNENTRY p
                                    LEFT JOIN PMBOM p2 ON p2.CODE = p.PMBOMCODE
                                    LEFT JOIN DEPARTMENT d ON d.CODE = p2.DEPARTMENTCODE
                                    LEFT JOIN PMWORKORDER p3 ON p3.PMBREAKDOWNENTRYCODE = p.CODE
                                    LEFT JOIN PMWORKORDERACTIVITYSPARES p4 ON p4.PMWORKORDDLTPMWORKORDERCODE = p3.CODE
                                    LEFT JOIN UNITOFMEASURE u ON u.CODE = p4.QUANTITYUOMCODE
                                    WHERE
                                        p.PMBOMCODE = '$no_mesin'
                                    AND p.COUNTERCODE = 'PBD007'
                                    ORDER BY
                                        p.IDENTIFIEDDATE ASC";
                $q_breakdown_header   = db2_exec($conn1, $query_breakdown);
                $row_breakdown_header = db2_fetch_assoc($q_breakdown_header);
            ?>
            <tr class="row0">
                <td class="column0 style5 null style9" colspan="2" rowspan="2"><img src="images/logoitti.png" width="65" height="60"></td>
                <td class="column2 style7 s style12" colspan="3" rowspan="2">KARTU RIWAYAT MESIN DAN PRASARANA</td>
                <td class="column5 style13 s style14" colspan="2">No Form : 14 - 04</td>
            </tr>
            <tr class="row1">
                <td class="column5 style15 s style16" colspan="2">No Revisi : 00</td>
            </tr>
            <tr class="row2">
                <td class="column0 style17 null style17" colspan="7"></td>
            </tr>
            <tr class="row3">
                <td class="column0 style29 s style30" colspan="2">Mesin</td>
                <td class="column2 style1 null style1" colspan="2"><?php echo $row_breakdown_header['MESIN'] ?></td>
                <td class="column4 style31 s">Type</td>
                <td class="column5 style1 null style2" colspan="2">&nbsp;</td>
            </tr>
            <tr class="row4">
                <td class="column0 style32 s style33" colspan="2">No. Mesin</td>
                <td class="column2 style3 null style3" colspan="2"><?php echo $row_breakdown_header['NO_MESIN'] ?></td>
                <td class="column4 style34 s">Document</td>
                <td class="column5 style3 null style4" colspan="2">&nbsp;</td>
            </tr>
            <tr class="row5">
                <td class="column0 style20 null style20" colspan="7"></td>
            </tr>
            <tr class="row6">
                <td class="column0 style21 s">No</td>
                <td class="column1 style22 s">Tanggal</td>
                <td class="column2 style23 s style24" colspan="5">Kegiatan</td>
            </tr>
            <?php
                $q_breakdown = db2_exec($conn1, $query_breakdown);
            ?>
<?php $no = 1;while ($row_breakdown = db2_fetch_assoc($q_breakdown)) {?>
                <tr class="row7">
                    <td class="column0 style25 null"><?php echo $no++; ?></td>
                    <td class="column1 style26 null"><?php echo date("d-M-Y", strtotime($row_breakdown['TANGGAL'])); ?></td>
                    <td class="column2 style27 null style28" colspan="5">
                        <a href="cetak_kartu_riwayat_detail.php?kode=<?php echo urlencode($row_breakdown['NO_MESIN']); ?>&tanggal=<?php echo urlencode($row_breakdown['TANGGAL']); ?>&workordercode=<?php echo urlencode($row_breakdown['WORKORDERCODE']); ?>" target="_blank" style="color: black; text-decoration: none;">
                            <?php echo $row_breakdown['KEGIATAN']; ?> -<?php echo $row_breakdown['SPAREPARTS']; ?>
                        </a>
                    </td>
                </tr>
            <?php }?>
            <tr class="row8">
                <td class="column0 style19 null"></td>
                <td class="column1 style19 null"></td>
                <td class="column2 style19 null"></td>
                <td class="column3 style19 null"></td>
                <td class="column4 style19 null"></td>
                <td class="column5 style19 null"></td>
                <td class="column6 style19 null"></td>
            </tr>
            <tr class="row9">
                <td class="column0 style19 null"></td>
                <td class="column1 style19 null"></td>
                <td class="column2 style19 null"></td>
                <td class="column3 style19 null"></td>
                <td class="column4 style19 null"></td>
                <td class="column5 style19 null"></td>
                <td class="column6 style19 null"></td>
            </tr>
            <tr class="row10">
                <td class="column0 style19 null"></td>
                <td class="column1 style19 null"></td>
                <td class="column2 style19 null"></td>
                <td class="column3 style19 null"></td>
                <td class="column4 style19 null"></td>
                <td class="column5 style19 null"></td>
                <td class="column6 style19 null"></td>
            </tr>
            <tr class="row11">
                <td class="column0 style19 null"></td>
                <td class="column1 style19 null"></td>
                <td class="column2 style19 null"></td>
                <td class="column3 style19 null"></td>
                <td class="column4 style19 null"></td>
                <td class="column5 style19 null"></td>
                <td class="column6 style19 null"></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
