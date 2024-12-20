<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<div class="page-content container-fluid">
      <div class="row">
        <div class="col-md-6">
          <!-- Panel Wizard Form -->
          <div class="panel" id="exampleWizardForm">
            <div class="panel-heading">
              <h3 class="panel-title">Wizard Form</h3>
            </div>
            <div class="panel-body">
              <!-- Steps -->
              <div class="steps steps-sm row" data-plugin="matchHeight" data-by-row="true" role="tablist">
                <div class="step col-md-4 current" data-target="#exampleAccount" role="tab">
                  <span class="step-number">1</span>
                  <div class="step-desc">
                    <span class="step-title">Account</span>
                    <p>Login for identification</p>
                  </div>
                </div>

                <div class="step col-md-4" data-target="#exampleBilling" role="tab">
                  <span class="step-number">2</span>
                  <div class="step-desc">
                    <span class="step-title">Billing</span>
                    <p>Pay for the bill</p>
                  </div>
                </div>

                <div class="step col-md-4" data-target="#exampleGetting" role="tab">
                  <span class="step-number">3</span>
                  <div class="step-desc">
                    <span class="step-title">Getting</span>
                    <p>Waiting for the goods</p>
                  </div>
                </div>
              </div>
              <!-- End Steps -->

              <!-- Wizard Content -->
              <div class="wizard-content">
                <div class="wizard-pane active" id="exampleAccount" role="tabpanel">
                  <form id="exampleAccountForm">
                    <div class="form-group">
                      <label class="control-label" for="inputUserName">Username</label>
                      <input type="text" class="form-control" id="inputUserName" name="username" required="required">
                    </div>
                    <div class="form-group">
                      <label class="control-label" for="inputPassword">Password</label>
                      <input type="password" class="form-control" id="inputPassword" name="password"
                      required="required">
                    </div>
                  </form>
                </div>
                <div class="wizard-pane" id="exampleBilling" role="tabpanel">
                  <form id="exampleBillingForm">
                    <div class="form-group">
                      <label class="control-label" for="inputCardNumber">Card Number</label>
                      <input type="text" class="form-control" id="inputCardNumber" name="number" placeholder="Card number">
                    </div>
                    <div class="form-group">
                      <label class="control-label" for="inputCVV">CVV</label>
                      <input type="text" class="form-control" id="inputCVV" name="cvv" placeholder="CVV">
                    </div>
                  </form>
                </div>
                <div class="wizard-pane" id="exampleGetting" role="tabpanel">
                  <div class="text-center margin-vertical-20">
                    <i class="icon wb-check font-size-40" aria-hidden="true"></i>
                    <h4>We got your order. Your product will be shipping soon.</h4>
                  </div>
                </div>
              </div>
              <!-- End Wizard Content -->

            </div>
          </div>
          <!-- End Panel Wizard One Form -->
        </div>

        <div class="col-md-6">
          <!-- Panel Wizard Form Container -->
          <div class="panel" id="exampleWizardFormContainer">
            <div class="panel-heading">
              <h3 class="panel-title">Pearls Steps</h3>
            </div>
            <div class="panel-body">
              <!-- Steps -->
              <div class="pearls row">
                <div class="pearl current col-xs-4">
                  <div class="pearl-icon"><i class="icon wb-user" aria-hidden="true"></i></div>
                  <span class="pearl-title">Account Info</span>
                </div>
                <div class="pearl col-xs-4">
                  <div class="pearl-icon"><i class="icon wb-payment" aria-hidden="true"></i></div>
                  <span class="pearl-title">Billing Info</span>
                </div>
                <div class="pearl col-xs-4">
                  <div class="pearl-icon"><i class="icon wb-check" aria-hidden="true"></i></div>
                  <span class="pearl-title">Confirmation</span>
                </div>
              </div>
              <!-- End Steps -->

              <!-- Wizard Content -->
              <form class="wizard-content" id="exampleFormContainer">
                <div class="wizard-pane active" role="tabpanel">
                  <div class="form-group">
                    <label class="control-label" for="inputUserNameOne">Username</label>
                    <input type="text" class="form-control" id="inputUserNameOne" name="username" required="required">
                  </div>
                  <div class="form-group">
                    <label class="control-label" for="inputPasswordOne">Password</label>
                    <input type="password" class="form-control" id="inputPasswordOne" name="password"
                    required="required">
                  </div>
                </div>
                <div class="wizard-pane" id="exampleBillingOne" role="tabpanel">
                  <div class="form-group">
                    <label class="control-label" for="inputCardNumberOne">Card Number</label>
                    <input type="text" class="form-control" id="inputCardNumberOne" name="number" placeholder="Card number">
                  </div>
                  <div class="form-group">
                    <label class="control-label" for="inputCVVOne">CVV</label>
                    <input type="text" class="form-control" id="inputCVVOne" name="cvv" placeholder="CVV">
                  </div>
                </div>
                <div class="wizard-pane" id="exampleGettingOne" role="tabpanel">
                  <div class="text-center margin-vertical-20">
                    <h4>Please confrim your order.</h4>
                    <div class="table-responsive">
                      <table class="table table-hover text-right">
                        <thead>
                          <tr>
                            <th class="text-center">#</th>
                            <th>Description</th>
                            <th class="text-right">Quantity</th>
                            <th class="text-right">Unit Cost</th>
                            <th class="text-right">Total</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="text-center">1</td>
                            <td class="text-left">Server hardware purchase</td>
                            <td>32</td>
                            <td>$75</td>
                            <td>$2152</td>
                          </tr>
                          <tr>
                            <td class="text-center">2</td>
                            <td class="text-left">Office furniture purchase</td>
                            <td>15</td>
                            <td>$169</td>
                            <td>$4169</td>
                          </tr>
                          <tr>
                            <td class="text-center">3</td>
                            <td class="text-left">Company Anual Dinner Catering</td>
                            <td>69</td>
                            <td>$49</td>
                            <td>$1260</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </form>
              <!-- Wizard Content -->
            </div>
          </div>
          <!-- End Panel Wizard Form Container -->
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <!-- Panel Wizard Pager -->
          <div class="panel" id="exampleWizardPager">
            <div class="panel-heading">
              <div class="panel-actions"></div>
              <h3 class="panel-title">Wizard Pager</h3>
            </div>
            <div class="panel-body">
              <div class="wizard-content">
                <div class="wizard-pane active" role="tabpanel">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent
                  libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem
                  at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris.
                  Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum
                  lacinia arcu eget nulla. Class aptent taciti sociosqu ad litora
                  torquent per conubia nostra, per inceptos himenaeos.
                </div>
                <div class="wizard-pane" role="tabpanel">
                  Sed dignissim lacinia nunc. Curabitur tortor. Pellentesque nibh. Aenean quam. In
                  scelerisque sem at dolor. Maecenas mattis. Sed convallis tristique
                  sem. Proin ut ligula vel nunc egestas porttitor. Morbi lectus
                  risus, iaculis vel, suscipit quis, luctus non, massa. Fusce ac
                  turpis quis ligula lacinia aliquet. Mauris ipsum. Nulla metus
                  metus, ullamcorper vel, tincidunt sed, euismod in, nibh.
                </div>
                <div class="wizard-pane" role="tabpanel">
                  Quisque volutpat condimentum velit. Class aptent taciti sociosqu ad litora torquent
                  per conubia nostra, per inceptos himenaeos. Nam nec ante. Sed
                  lacinia, urna non tincidunt mattis, tortor neque adipiscing diam,
                  a cursus ipsum ante quis turpis. Nulla facilisi. Ut fringilla.
                  Suspendisse potenti. Nunc feugiat mi a tellus consequat imperdiet.
                  Vestibulum sapien. Proin quam. Etiam ultrices.
                </div>
              </div>
            </div>
          </div>
          <!-- End Panel Wizard Progressbar -->
        </div>

        <div class="col-md-6">
          <!-- Panel Wizard Progressbar -->
          <div class="panel" id="exampleWizardProgressbar">
            <div class="panel-heading">
              <div class="panel-actions">
                <div class="progress progress-xs">
                  <div class="progress-bar active" style="width: 33.3%">
                    <span class="sr-only">1/3</span>
                  </div>
                </div>
              </div>
              <h3 class="panel-title">Wizard Progressbar</h3>
            </div>
            <div class="panel-body">
              <div class="wizard-content">
                <div class="wizard-pane active" role="tabpanel">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent
                  libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem
                  at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris.
                  Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum
                  lacinia arcu eget nulla. Class aptent taciti sociosqu ad litora
                  torquent per conubia nostra, per inceptos himenaeos.
                </div>
                <div class="wizard-pane" role="tabpanel">
                  Sed dignissim lacinia nunc. Curabitur tortor. Pellentesque nibh. Aenean quam. In
                  scelerisque sem at dolor. Maecenas mattis. Sed convallis tristique
                  sem. Proin ut ligula vel nunc egestas porttitor. Morbi lectus
                  risus, iaculis vel, suscipit quis, luctus non, massa. Fusce ac
                  turpis quis ligula lacinia aliquet. Mauris ipsum. Nulla metus
                  metus, ullamcorper vel, tincidunt sed, euismod in, nibh.
                </div>
                <div class="wizard-pane" role="tabpanel">
                  Quisque volutpat condimentum velit. Class aptent taciti sociosqu ad litora torquent
                  per conubia nostra, per inceptos himenaeos. Nam nec ante. Sed
                  lacinia, urna non tincidunt mattis, tortor neque adipiscing diam,
                  a cursus ipsum ante quis turpis. Nulla facilisi. Ut fringilla.
                  Suspendisse potenti. Nunc feugiat mi a tellus consequat imperdiet.
                  Vestibulum sapien. Proin quam. Etiam ultrices.
                </div>
              </div>
            </div>
          </div>
          <!-- End Panel Wizard Progressbar -->
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <!-- Example Wizard Tabs -->
          <div class="margin-bottom-30">
            <div class="nav-tabs-inverse nav-tabs-vertical" id="exampleWizardTabs">
              <ul class="nav nav-tabs nav-tabs-solid" data-plugin="nav-tabs" role="tablist">
                <li class="active" role="presentation">
                  <a data-toggle="tab" href="#exampleTab1" aria-controls="exampleTab1" role="tab">
                    <i class="icon wb-home margin-0" aria-hidden="true"></i>
                  </a>
                </li>
                <li role="presentation">
                  <a data-toggle="tab" href="#exampleTab2" aria-controls="exampleTab2" role="tab">
                    <i class="icon wb-settings margin-0" aria-hidden="true"></i>
                  </a>
                </li>
                <li role="presentation">
                  <a data-toggle="tab" href="#exampleTab3" aria-controls="exampleTab3" role="tab">
                    <i class="icon wb-star margin-0" aria-hidden="true"></i>
                  </a>
                </li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane active" id="exampleTab1" role="tabpanel">
                  Concludaturque conspiratione maiestatis licebit inflammat istis stulti infimum
                  timeam, dolere profecta inferiorem augendas exaudita e nominata
                  naturalem dignissimos. Sit. Omnis claris tranquillitate fidelissimae
                  vitium pro. Aegritudines amori cur ipso et equos expectant oculis.
                  Hausta efficeret pariuntur gerendarum scribendi, admonere cetero.
                </div>
                <div class="tab-pane" id="exampleTab2" role="tabpanel">
                  Carere quamque praetermittenda artem tibique doctissimos una aequitatem, putemus
                  petentium imperii nesciunt homero lucilius inpotenti provocatus
                  manilium congressus. Molita debent dixissem putet inesse primo
                  reliqui intellegebat. Finitum, officiis initia t regula manum
                  ocurreret sole magnis appellat. Expedita leviora graviter cupiditates.
                </div>
                <div class="tab-pane" id="exampleTab3" role="tabpanel">
                  Minima, avocent latinas consoletur habendus dignissimos reprehenderit alii amaret
                  efficerent corpora, tranquillat quanto quantumcumque novum didicisset
                  explicatam, afferat imperiis firmam, futurove ponendam uberiora
                  augendas unde sensum gravis familiaritatem fames humili. Negarent
                  ultimum, reliquerunt suo pueri sponte sublata confirmare contentiones,
                  eamque.
                </div>
              </div>
            </div>
          </div>
          <!-- End Example Wizard Tabs -->
        </div>


        <div class="col-md-6">
          <!-- Example Wizard Accordion -->
          <div class="margin-bottom-30">
            <div class="panel-group" id="exampleWizardAccordion" aria-multiselectable="true"
            role="tablist">
              <div class="panel">
                <div class="panel-heading" id="exampleHeading1" role="tab">
                  <a class="panel-title" data-toggle="collapse" href="#exampleCollapse1" data-parent="#exampleWizardAccordion"
                  aria-expanded="true" aria-controls="exampleCollapse1">
                  Collapsible Group Item #1
                </a>
                </div>
                <div class="panel-collapse collapse in" id="exampleCollapse1" aria-labelledby="exampleHeading1"
                role="tabpanel">
                  <div class="panel-body">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent
                    libero. Sed cursus ante dapibus diam. Sed nisi.
                  </div>
                </div>
              </div>
              <div class="panel">
                <div class="panel-heading" id="exampleHeading2" role="tab">
                  <a class="panel-title collapsed" data-toggle="collapse" href="#exampleCollapse2"
                  data-parent="#exampleWizardAccordion" aria-expanded="false" aria-controls="exampleCollapse2">
                  Collapsible Group Item #2
                </a>
                </div>
                <div class="panel-collapse collapse" id="exampleCollapse2" aria-labelledby="exampleHeading2"
                role="tabpanel">
                  <div class="panel-body">
                    Quisque volutpat condimentum velit. Class aptent taciti sociosqu ad litora torquent
                    per conubia nostra, per inceptos himenaeos.
                  </div>
                </div>
              </div>

              <div class="panel">
                <div class="panel-heading" id="exampleHeading3" role="tab">
                  <a class="panel-title collapsed" data-toggle="collapse" href="#exampleCollapse3"
                  data-parent="#exampleWizardAccordion" aria-expanded="false" aria-controls="exampleCollapse3">
                  Collapsible Group Item #3
                </a>
                </div>
                <div class="panel-collapse collapse" id="exampleCollapse3" aria-labelledby="exampleHeading3"
                role="tabpanel">
                  <div class="panel-body">
                    Sed dignissim lacinia nunc. Curabitur tortor. Pellentesque nibh. Aenean quam. In
                    scelerisque sem at dolor. Maecenas mattis.
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- End Example Wizard Accordion -->
        </div>
      </div>

    </div>
</body>
</html>