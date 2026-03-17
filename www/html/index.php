<?php
const HTML_ACTIVE = ' active';
const HTML_CHECKED = ' checked';
const HTML_NO_RUN = 'no run';
const HTML_RESULT_FOR = "        <h3>Result for %s (%s)%s</h3>\n";
const HTML_SHOW = ' show';
const HTML_TRUE = 'true';
const HTML_SHIBBOLETH_LOGIN = 'Shibboleth.sso/Login?entityID=';

if (isset($_SERVER['Shib-Identity-Provider']) ) {
  $result = true;
  $idp = $_SERVER['Shib-Identity-Provider'];
  $instructionsSelected = 'false';
  $instructionsShow = '';
  //Load composer's autoloader
  require_once '../vendor/autoload.php';
  $displayName = isset($_SERVER['Meta-displayName']) ? $_SERVER['Meta-displayName'] : '';
} else {
  $result = false;
  $idp = '';
  $instructionsSelected = HTML_TRUE;
  $instructionsShow = HTML_SHOW;
  //Load composer's autoloader
  require_once 'vendor/autoload.php';
}

$config = new \releasecheck\Configuration();
$federation = $config->getFederation();

$idpCheck = $config->getExtendedClass('IdPCheck', 'mfa');

$testSuite = $config->getExtendedClass('TestSuite');
$html = $config->getExtendedClass('HTML');
$display = $config->getExtendedClass('Display');

# Default values
$attributesActive='';
$attributesSelected='false';
$attributesShow='';
#
$entityCategoryActive='';
$entityCategorySelected='false';
$entityCategoryShow='';
#
$esiActive='';
$esiSelected='false';
$esiShow='';
#
$accActive='';
$accSelected='false';
$accShow='';

if (isset($_GET['tab'])) {
  switch ($_GET['tab']) {
    case 'acc' :
      $accActive = HTML_ACTIVE;
      $accSelected = HTML_TRUE;
      $accShow = HTML_SHOW;
      $tab = 'acc';
      if (isset($_POST['accr'])) {
        createRedirect($_POST, $result, $idp);
      } elseif ($_GET['accr'] && isset($_GET['testForceAuthn'])) {
        createRedirect(array('accr' => $_GET['accr'], 'force' => true), $result, $idp);
      }
      break;
    case 'entityCategory' :
      $entityCategoryActive = HTML_ACTIVE;
      $entityCategorySelected = HTML_TRUE;
      $entityCategoryShow = HTML_SHOW;
      $tab = 'entityCategory';
      break;
    case 'esi' :
      $esiActive = HTML_ACTIVE;
      $esiSelected = HTML_TRUE;
      $esiShow = HTML_SHOW;
      $tab = 'esi';
      break;
    default:
      $attributesActive = HTML_ACTIVE;
      $attributesSelected = HTML_TRUE;
      $attributesShow = HTML_SHOW;
      $tab = 'attributes';
  }
} else {
  $attributesActive = HTML_ACTIVE;
  $attributesSelected = HTML_TRUE;
  $attributesShow = HTML_SHOW;
  $tab = 'attributes';
}
$html->showHTMLHead();
$html->showContentHeader();
printf('    <div class="row">
      <div class="col">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link%s" id="attributes-tab" data-toggle="tab" href="#attributes"
              role="tab" aria-controls="attributes" aria-selected="%s">Attributes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link%s" id="acc-tab" data-toggle="tab" href="#acc"
              role="tab" aria-controls="acc" aria-selected="%s">Authentication</a>
          </li>
          <li class="nav-item">
            <a class="nav-link%s" id="entityCategory-tab" data-toggle="tab"
              href="#entityCategory" role="tab" aria-controls="entityCategory"
              aria-selected="%s">Entity category</a>
          </li>
          <li class="nav-item">
            <a class="nav-link%s" id="esi-tab" data-toggle="tab" href="#esi"
              role="tab" aria-controls="esi" aria-selected="%s">ESI</a>
          </li>
        </ul>
      </div>
      <div class="col-4 text-right">%s',
  $attributesActive, $attributesSelected,
  $accActive, $accSelected,
  $entityCategoryActive, $entityCategorySelected,
  $esiActive, $esiSelected, "\n");
if ($result) {
        printf ("        <p><span style=\"white-space: nowrwap\"><b>%s</b><br>%s</span></p>\n",$displayName,$idp);
        $admin = $config->getExtendedClass('Admin');
        $adminButton = $admin->checkAccess() ? '<a href="admin.php">
          <button type="button" class="btn btn-primary">Admin</button>
        </a>' : '';
} else {
  $adminButton = '';
}
printf ('        %s
        <a data-toggle="collapse" href="#selectIdP" aria-expanded="false" aria-controls="selectIdP">
          <button type="button" class="btn btn-outline-primary">%s IdP</button>
        </a>
      </div>
    </div>
    <br>


    <div class="collapse multi-collapse" id="selectIdP">
      <h2>Select IdP</h2>
      <br>
      <div class="row">
        <div class="col">
          <div id="DS-Thiss"></div>
        </div>
      </div>
    </div><!-- end collapse selectIdP -->

    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade%s%s" id="attributes"
        role="tabpanel" aria-labelledby="attributes-tab">
        <h2>Released attributes from IdP</h2>
        <br>
        <div class="row">
          <div class="col">
            <a href="https://%s/result">
              <button type="button" class="btn btn-success">%s and show attributes</button>
            </a>
          </div>
        </div>
        <h3>
          <i id="attributes-instructions-icon" class="fas fa-chevron-circle-%s"></i>
          <a data-toggle="collapse" href="#attributes-instructions" aria-expanded="%s" aria-controls="attributes-instructions">Instructions</a>
        </h3>
        <div class="collapse%s multi-collapse" id="attributes-instructions">
          %s
        </div><!-- end collapse -->%s',
  $adminButton, $result ? "Change" : "Select", $attributesShow, $attributesActive,
  $config->basename(), $result ? "Refresh" : "Login" , $result ? "right" : "down",
  $instructionsSelected, $instructionsShow, $federation['instructionsAttributes'], "\n");

  $collapseIcons[] = "attributes-instructions";

if ($result) {
  printf (HTML_RESULT_FOR, $displayName, $idp, '');
  $display->showAttributeList();
  $display->showIdpMetadataInfo();
  $display->showIdpSessionInfo();
}
printf('      </div><!-- End tab-pane attributes -->
      <div class="tab-pane fade%s%s" id="acc" role="tabpanel" aria-labelledby="acc-tab">
        <h2>%s AuthnContextClassRef tester</h2>
        <br>
        <h3>
          <i id="acc-instructions-icon" class="fas fa-chevron-circle-%s"></i>
          <a data-toggle="collapse" href="#acc-instructions" aria-expanded="%s"
            aria-controls="acc-instructions">
            Instructions
          </a>
        </h3>
        <div class="collapse%s multi-collapse" id="acc-instructions">
          <p>Different tests for AuthnContextClassRef. The restults from this tests are NOT saved exept for tests done with REFEDS MFA.</p>
        </div><!-- end collapse -->%s',
  $accShow, $accActive, $federation['displayName'],
  $result ? "right" : "down", $instructionsSelected, $instructionsShow, "\n");
$collapseIcons[] = "acc-instructions";
$accr = isset($_REQUEST['accr']) ? $_REQUEST['accr'] : 'none';
printf('        <div class="row">
          <div class="col">
            <form action="./?tab=acc" method="POST">
              <input type="radio" id="none" name="accr" value="none"%s>
              <label for="none">No authnContextClassRef</label><br>%s',
  $accr == 'none' ? HTML_CHECKED : '',
  "\n");
foreach ($idpCheck->getAccrOptions() as $key => $accrArray) {
  printf('              <input type="radio" id="%s" name="accr" value="%s"%s>
              <label for="%s">%s</label><br>%s',
    $key, $key, $key == $accr ? HTML_CHECKED : '',
    $key, $accrArray['description'],
    "\n");
}
printf('              <button type="submit" name="action" class="btn btn-success">Test</button><br>
            </form>
          </div>%s', "\n");
if ($result) {
  $expectedAccr = isset($idpCheck->accrOptions[$accr])
    ? $idpCheck->accrOptions[$accr]['value']
    : $_SERVER['Shib-AuthnContext-Class'];
  if ($expectedAccr == $_SERVER['Shib-AuthnContext-Class']) {
    printf('          <div class="col">
            <p>Got %sAuthnContext-Class (%s)</p>
            <p>Press button below to test with forceAuthn.<p>
            <a href="?tab=acc&accr=%s&testForceAuthn"><button type="button" class="btn btn-success">forceAuthN</button></a>
          </div>
        </div>%s', $accr == 'none' ? '' : 'expected ', $expectedAccr, $accr, "\n");
  } else {
    printf('        </div>%s', "\n");
  }
  $idpCheck->testACCR($accr);
} else {
  printf('        </div>%s', "\n");
}
printf('      </div><!-- End tab-pane acc -->
      <div class="tab-pane fade %s%s" id="entityCategory"
        role="tabpanel" aria-labelledby="entityCategory-tab">
        <h2>%s Best Practice Attribute Release check</h2>
        <br>
        <div class="row">
          <div class="col">
            <a href="https://assurance.%s/%s"><button type="button" class="btn btn-success">Run all tests automatically</button></a>
          </div>
          <div class="col">
            <a href="https://assurance.%s/%s"><button type="button" class="btn btn-success">Run tests manually</button></a>
          </div>%s',
  $entityCategoryShow, $entityCategoryActive,
  $federation['displayName'],
  $config->basename(),
  $result ?
    sprintf('Shibboleth.sso/Login?entityID=%s&target=%s', $idp,
      urlencode(sprintf('https://assurance.%s/?quickTest', $config->basename()))
    ) : '?quickTest',
  $config->basename(),
  $result ? HTML_SHIBBOLETH_LOGIN . $idp : '',
  "\n");
if (! $result ) {
  # Show button to display result after test-buttons
  printf('          <div class="col">
            <a href="https://%s/result/?tab=entityCategory">
              <button type="button" class="btn btn-success">Show results</button>
            </a>
          </div>%s', $config->basename(), "\n");
}
printf('        </div>
        <h3>
          <i id="entityCategory-instructions-icon" class="fas fa-chevron-circle-%s"></i>
          <a data-toggle="collapse" href="#entityCategory-instructions" aria-expanded="%s" aria-controls="entityCategory-instructions">Instructions</a>
        </h3>
        <div class="collapse%s multi-collapse" id="entityCategory-instructions">
          %s
          <ul style="list-style-type:none">%s',
  $result ? "right" : "down", $instructionsSelected, $instructionsShow, $federation['instructionsEntityCategory'], "\n");
foreach ($testSuite->getECTests() as $test) {
  printf ('            <li>
              <a href="https://%s.%s/Shibboleth.sso/Login?target=%s">%s</a> - %s
            </li>%s', $test, $config->basename(), urlencode(sprintf('https://%s.%s/?singleTest', $test, $config->basename())), $test,
          $testSuite->getTestName($test), "\n");
}
printf ('          </ul>
          %s
        </div><!-- end collapse -->%s', $federation['instructionsEntityCategoryEnd'], "\n");
$collapseIcons[] = "entityCategory-instructions";
if ($result) {
  $testrun = $display->getTestruns($idp, 'entityCategory');
  printf (HTML_RESULT_FOR, $displayName,$idp, $testrun['time'] == HTML_NO_RUN ? '' : ' ('.$testrun['time'].')');
  $display->showResultsECTests($idp, $testrun);
}
printf('      </div><!-- End tab-pane entityCategory -->
      <div class="tab-pane fade%s%s" id="esi" role="tabpanel" aria-labelledby="esi-tab">
        <h2>%s Best Practice Attribute Release check</h2>
        <br>
        <div class="row">
          <div class="col">
            <a href="https://esi.%s/%s">
              <button type="button" class="btn btn-success">Run tests</button>
            </a>
          </div>%s',
  $esiShow, $esiActive, $federation['displayName'], $config->basename(), $result ? HTML_SHIBBOLETH_LOGIN . $idp : '',
  "\n");
if (! $result ) {
  printf('          <div class="col">
            <a href="https://%s/result/?tab=esi">
              <button type="button" class="btn btn-success">Show results</button>
            </a>
          </div>%s', $config->basename(), "\n");
}
printf('        </div>
        <h3>
          <i id="esi-instructions-icon" class="fas fa-chevron-circle-%s"></i>
          <a data-toggle="collapse" href="#esi-instructions" aria-expanded="%s"
            aria-controls="esi-instructions">
            Instructions
          </a>
        </h3>
        <div class="collapse%s multi-collapse" id="esi-instructions">
          <p>European Student Identifier uses the entity category https://myacademicid.org/entity-categories/esi
            for release of attributes from the user\'s identity provider. This test verifies that all required
            attributes are released during login.</p>
        </div><!-- end collapse -->%s',
  $result ? "right" : "down", $instructionsSelected, $instructionsShow, "\n");
$collapseIcons[] = "esi-instructions";
if ($result) {
  $testrun = $display->getTestruns($idp, 'esi');
  printf (HTML_RESULT_FOR, $displayName,$idp, $testrun['time'] == HTML_NO_RUN ? '' : ' ('.$testrun['time'].')');
  $display->showResultsESI($idp, $testrun);
}

printf("      </div><!-- End tab-pane esi -->
      <!-- Include the Seamless Access Sign in Button & Discovery Service -->
      <script src=\"//%s/thiss.js\"></script>
      <script>
        window.onload = function() {
          // Render the Seamless Access button
          thiss.DiscoveryComponent({
            loginInitiatorURL: 'https://%s/Shibboleth.sso/%s?target=https://%s/result',
          }).render('#DS-Thiss');
        };
      </script>\n", $federation['DS'], $config->basename(), $federation['LoginURL'], $config->basename());
$html->showContentFooter();
$html->showScripts($collapseIcons);

function createRedirect($post, $result, $idp) {
  global $config, $idpCheck;
  $redirectURL = sprintf('https://%s/Shibboleth.sso/Login?target=%s',
    $config->basename(),
    urlencode(sprintf('https://%s/result?tab=acc&accr=%s%s',
      $config->basename(),
      $post['accr'],
      isset($post['force']) && $post['force'] ? '&forceAuthn' : ''))
  );
  $redirectURL .= $result ? sprintf('&entityID=%s', urlencode($idp)) : '';
  $redirectURL .= isset($idpCheck->getAccrOptions()[$post['accr']])
    ? sprintf('&authnContextClassRef=%s',
      $idpCheck->getAccrOptions()[$post['accr']]['value'])
    : '';
  $redirectURL .= isset($post['force']) && $post['force'] ? '&forceAuthn=true' : '';
  header('Location: ' . $redirectURL);
  exit;
}
