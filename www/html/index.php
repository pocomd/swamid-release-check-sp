<?php
const HTML_ACTIVE = ' active';
const HTML_NO_RUN = 'no run';
const HTML_RESULT_FOR = "        <h3>Result for %s (%s)%s</h3>\n";
const HTML_SHOW = ' show';
const HTML_TRUE = 'true';
const HTML_SHIBBOLETH_LOGIN = 'Shibboleth.sso/Login?entityID=';

if (isset($_SERVER['Shib-Identity-Provider']) ) {
  $result = true;
  $IdP = $_SERVER['Shib-Identity-Provider'];
  $instructionsSelected = 'false';
  $instructionsShow = '';
  //Load composer's autoloader
  require_once '../vendor/autoload.php';
  $displayName = isset($_SERVER['Meta-displayName']) ? $_SERVER['Meta-displayName'] : '';
} else {
  $result = false;
  $instructionsSelected = HTML_TRUE;
  $instructionsShow = HTML_SHOW;
  //Load composer's autoloader
  require_once 'vendor/autoload.php';
}

$config = new \releasecheck\Configuration();
$federation = $config->getFederation();

$testSuite = $config->getExtendedClass('TestSuite');
$html = $config->getExtendedClass('HTML');

$html->showHTMLHead();
$html->showContentHeader();
$display = new \releasecheck\Display();

# Default values
$attributesActive='';
$attributesSelected='false';
$attributesShow='';
#
$entityCategoryActive='';
$entityCategorySelected='false';
$entityCategoryShow='';
#
$mfaActive='';
$mfaSelected='false';
$mfaShow='';
#
$esiActive='';
$esiSelected='false';
$esiShow='';

if (isset($_GET['tab'])) {
  switch ($_GET['tab']) {
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
    case 'mfa' :
      $mfaActive = HTML_ACTIVE;
      $mfaSelected = HTML_TRUE;
      $mfaShow = HTML_SHOW;
      $tab = 'mfa';
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
printf('    <div class="row">
      <div class="col">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link%s" id="attributes-tab" data-toggle="tab" href="#attributes"
              role="tab" aria-controls="attributes" aria-selected="%s">Attributes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link%s" id="entityCategory-tab" data-toggle="tab"
              href="#entityCategory" role="tab" aria-controls="entityCategory"
              aria-selected="%s">Entity category</a>
          </li>
          <li class="nav-item">
            <a class="nav-link%s" id="mfa-check-tab" data-toggle="tab" href="#mfa-check"
              role="tab" aria-controls="mfa-check" aria-selected="%s">MFA</a>
          </li>
          <li class="nav-item">
            <a class="nav-link%s" id="esi-tab" data-toggle="tab" href="#esi"
              role="tab" aria-controls="esi" aria-selected="%s">ESI</a>
          </li>
        </ul>
      </div>
      <div class="col-4 text-right">%s',
  $attributesActive, $attributesSelected, $entityCategoryActive, $entityCategorySelected,
  $mfaActive, $mfaSelected, $esiActive, $esiSelected, "\n");
if ($result) {
        printf ("        <p><span style=\"white-space: nowrwap\"><b>%s</b><br>%s</span></p>\n",$displayName,$IdP);
}
printf ('        <a data-toggle="collapse" href="#selectIdP" aria-expanded="false" aria-controls="selectIdP">
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
  $result ? "Change" : "Select", $attributesShow, $attributesActive,
  $config->basename(), $result ? "Refresh" : "Login" , $result ? "right" : "down",
  $instructionsSelected, $instructionsShow, $federation['instructionsAttributes'], "\n");

  $collapseIcons[] = "attributes-instructions";

  if ($result) {
    printf (HTML_RESULT_FOR, $displayName, $IdP, '');
    $display->showAttributeList();
    $display->showIdpMetadataInfo();
    $display->showIdpSessionInfo();
  }
  printf('      </div><!-- End tab-pane attributes -->
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
      sprintf('Shibboleth.sso/Login?entityID=%s&target=%s', $IdP,
        urlencode(sprintf('https://assurance.%s/?quickTest', $config->basename()))
      ) : '?quickTest',
    $config->basename(),
    $result ? HTML_SHIBBOLETH_LOGIN . $IdP : '',
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
    if ($testruns = $display->getTestruns($IdP, 'entityCategory')) {
      $testrun = $testruns[0];
      if (count($testruns) > 1) {
        print "          <h4>Other results</h4>
          <ul>\n";
        foreach($testruns as $run) {
          printf('            <li><a href="./?tab=entityCategory&id=%d">%s</a></li>%s', $run['id'], $run['time'], "\n");
          # Check if thus run is requested run. In that vase save this run
          if (isset($_GET['id']) && $_GET['id'] == $run['id']) {
            $testrun = $run;
          }
        }
        print "          </ul>\n";
      }
    } else {
      $testrun = array ('id' => 0, 'time' => HTML_NO_RUN);
    }
    printf (HTML_RESULT_FOR, $displayName,$IdP, $testrun['time'] == HTML_NO_RUN ? '' : ' ('.$testrun['time'].')');
    $display->showResultsECTests($IdP, $testrun['id']);
  }
  printf('      </div><!-- End tab-pane entityCategory -->
      <div class="tab-pane fade%s%s" id="mfa-check"
        role="tabpanel" aria-labelledby="mfa-check-tab">
        <h2>SWAMID Best Practice MFA check</h2>
        <br>
        <div class="row">
          <div class="col">
            <a href="https://mfa.%s/%s">
              <button type="button" class="btn btn-success">Run tests</button>
            </a>
          </div>%s',
    $mfaShow, $mfaActive, $config->basename(), $result ? HTML_SHIBBOLETH_LOGIN . $IdP : '', "\n");
  if (! $result ) {
    printf('          <div class="col">
            <a href="https://%s/result/?tab=mfa">
              <button type="button" class="btn btn-success">Show results</button>
            </a>
          </div>%s', $config->basename(), "\n");
  }
  printf('        </div>
        <h3>
          <i id="mfa-instructions-icon" class="fas fa-chevron-circle-%s"></i>
          <a data-toggle="collapse" href="#mfa-instructions" aria-expanded="%s"
            aria-controls="mfa-instructions">
            Instructions
          </a>
        </h3>
        <div class="collapse%s multi-collapse" id="mfa-instructions">
          <p>SWAMID MFA test. This is a two part test<ol>
            <li>REFEDS MFA without forceAuthn</li>
            <li>REFEDS MFA with forceAuthn</li>
          </ol></p>
        </div><!-- end collapse -->%s',
    $result ? "right" : "down", $instructionsSelected, $instructionsShow, "\n");
  $collapseIcons[] = "mfa-instructions";
  if ($result) {
    if ($testruns = $display->getTestruns($IdP, 'mfa')) {
      $testrun = $testruns[0];
      if (count($testruns) > 1) {
        print "          <h4>Other results</h4>
          <ul>\n";
        foreach($testruns as $run) {
          printf('            <li><a href="./?tab=mfa&id=%d">%s</a></li>%s', $run['id'], $run['time'], "\n");
          # Check if thus run is requested run. In that vase save this run
          if (isset($_GET['id']) && $_GET['id'] == $run['id']) {
            $testrun = $run;
          }
        }
        print "          </ul>\n";
      }
    } else {
      $testrun = array ('id' => 0, 'time' => HTML_NO_RUN);
    }
    printf (HTML_RESULT_FOR, $displayName,$IdP, $testrun['time'] == HTML_NO_RUN ? '' : ' ('.$testrun['time'].')');
    $display->showResultsMFA($IdP, $testrun['id']);
  }
  printf('      </div><!-- End tab-pane mfa-check -->
      <div class="tab-pane fade%s%s" id="esi" role="tabpanel" aria-labelledby="esi-tab">
        <h2>%s Best Practice Attribute Release check</h2>
        <br>
        <div class="row">
          <div class="col">
            <a href="https://esi.%s/%s">
              <button type="button" class="btn btn-success">Run tests</button>
            </a>
          </div>%s',
    $esiShow, $esiActive, $federation['displayName'], $config->basename(), $result ? HTML_SHIBBOLETH_LOGIN . $IdP : '',
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
    if ($testruns = $display->getTestruns($IdP, 'esi')) {
      $testrun = $testruns[0];
      if (count($testruns) > 1) {
        print "          <h4>Other results</h4>
          <ul>\n";
        foreach($testruns as $run) {
          printf('            <li><a href="./?tab=esi&id=%d">%s</a></li>%s', $run['id'], $run['time'], "\n");
          # Check if thus run is requested run. In that vase save this run
          if (isset($_GET['id']) && $_GET['id'] == $run['id']) {
            $testrun = $run;
          }
        }
        print "          </ul>\n";
      }
    } else {
      $testrun = array ('id' => 0, 'time' => HTML_NO_RUN);
    }
    printf (HTML_RESULT_FOR, $displayName,$IdP, $testrun['time'] == HTML_NO_RUN ? '' : ' ('.$testrun['time'].')');
    $display->showResultsESI($IdP, $testrun['id']);
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
