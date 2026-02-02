<?php
$db = array(
  'servername'  => 'localhost',      # Name of DB server
  'username'    => 'admin',          # Username for DB
  'password'    => 'adminpwd',       # Password for DB NOSONAR
  'name'        => 'releasecheck',   # Name of Database

  # optional parameter

  ###
  # The file path to the SSL certificate authority.
  # Activates PDO::MYSQL_ATTR_SSL_CA in options.
  ###
  # 'caPath' => '/etc/ssl/CA.pem',
);

$basename = 'release-check.<org>.<tld>';

$federation = array(
  'displayName' => 'SWAMID',
  # Admin users that should have access to ops.php
  'adminUsers' => array('adminuser1@federation.org', 'adminuser2@federation.org',
    'user1@inst1.org', 'user1@inst2.org'),

  # Urls could be relative or absolute depending on if they are hosted on the same box or not.
  'aboutURL' => 'https://edugain.org/about-edugain/what-is-edugain/',
  'contactURL' => 'https://edugain.org/contact/',
  'logoURL' => 'https://edugain.org/wp-content/uploads/2017/06/header_logo_small-1.gif',
  'logoWidth' => 163,
  'logoHeight' => 40,

  # Optional if you want to extend HTML and TestSuite with an extended version
  # See TestSuiteSWAMID and HTMLSWAMID for examples
  #'extend' => 'SWAMID',

  # Optional if you want to change backgroudColor on the page
  #'backgroundColor' => '#F05523',

  # Optional if you want to change DiscoveryService or want to replace LoginURL
  # If not set defaults to service.seamlessaccess.org and Login';
  #'DS' => 'service.seamlessaccess.org',
  #'LoginURL' => 'DS/seamless-access',

  # Optional if you want to fetch existiong IdP:s from a Metadata Tool
  #'metadataTool' => 'metadata.qa.swamid.se',

  # Optinal if you want to reuse session and not start a new testRun fore each session
  # true or false
  #'reuseSession' => true,

  'instructionsAttributes' => '<p>Click on the green button to see what attributes your Identity Provider releases.</p>
          <p>Description of all test available in the eduGAIN test suite:
            <ul>
              <li>The Attributes tab shows all attributes the service release to the entityId https://release-check.<org>.<tld>/shibboleth. The entityId uses the entity categories:<ul>
                <li>REFEDS Personalized Access Entity Category,</li>
                <li>REFEDS Research and Scholarship Entity Category, and</li>
                <li>REFEDS Data Protection Code of Conduct ver 2.0 Entity Category.</li>
              </ul></li>
              <li>The Entity category tab does an exetensive testing of that an Identity Provider follows
                Best Practice for attribute release via entity categories.</li>
              <li>The MFA tab checks if an Identity Provider is correctly configured for handling request
                for multi-factor login.</li>
              <li>The ESI tab verifies if the Identity Provider release the right attributes for the
                European Digital Student Service Infrastructure.</li>
            </ul>
          </p>',
  'instructionsEntityCategory' => '<p>In order for eduGAIN to work as effectively as possible for students and employees as well as for
            service providers and identity providers, eduGAIN recommends that service providers use
            entity categories to get the attributes that they require.</p>
          <p>In order for services within the eduGAIN federation to work as effectively as possible, eduGAIN recommends
            the use of entity categories. Entity categories benefits not only students and employees but also
            administrators of relying and identity providers by providing a
            stable framework for the release of attributes.</p>
          <p>The eduGAIN best practice attribute release check consists of the following tests:</p>',
  'instructionsEntityCategoryEnd' => '<p>Multiple Code of Conduct test require different attributes which the IdP either SHOULD or SHOULD NOT
            release in accordance REFEDS/GÉANT Code of Conduct.</p>',
);
