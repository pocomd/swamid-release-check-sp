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
          <p>Description of all test avaiable in the ' . $basename . ' identity federation test suite:
            <ul>
              <li>The Attributes tab shows all attributes the service release to the entityId https://release-check.<org>.<tld>/shibboleth. The entityId uses the entity categories:<ul>
                <li>REFEDS Personalized Access Entity Category,</li>
                <li>REFEDS Research and Scholarship Entity Category, and</li>
                <li>REFEDS Data Protection Code of Conduct ver 2.0 Entity Category including all
                  <a href="https://wiki.sunet.se/display/SWAMID/Entity+Category+attribute+release+in+SWAMID">
                  ' . $basename . ' Best Practice attributes</a>.
                </li>
              </ul></li>
              <li>The Entity category tab does an exetensive testing of that an Identity Provider follows
                ' . $basename . ' Best Practice attribute release via entity categories.</li>
              <li>The MFA tab checks if an Identity Provider is correctly configured for handling request
                for multi-factor login as expected by SWAMID.</li>
              <li>The ESI tab verifies if the Identity Provider release the right attributes for the
                European Digital Student Service Infrastructure.</li>
            </ul>
          </p>',
    'instructionsEntityCategory' => '<p>In order for ' . $basename . ' to work as effectively as possible for students and employees as well as for
            service providers and identity providers, ' . $basename . ' recommends that service providers use
            entity categories to get the attributes that they require.</p>
          <p>In order for services within the ' . $basename . ' federation to work as effectively as possible, ' . $basename . ' recommends
            the use of entity categories. Entity categories benefits not only students and employees but also
            administrators of relying and identity providers by providing a
            stable framework for the release of attributes.</p>
          <p>During autumn 2019, ' . $basename . ' has updated its entity category recommendations and these will be implemented
            in our production environment during 2020 and 2021.</p>
          <p>This service is designed to help administrators of identity providers verify that their
            IdP follows the new recommendations.</p>
          <p>SWAMID’s current recommendations for attribute release are available at
            <a href="https://wiki.sunet.se/display/SWAMID/SAML+WebSSO+Service+Provider+Best+Current+Practice">
              https://wiki.sunet.se/display/SWAMID/SAML+WebSSO+Service+Provider+Best+Current+Practice
            </a>.
          </p>
          <p>Example configuration for Shibboleth can be found in the section entitled “Example of metadata
            configuration, attribute resolvers and attribute filters” on the following wiki page
            <a href="https://wiki.sunet.se/display/SWAMID/SAML+WebSSO+Identity+Provider+Best+Current+Practice">
              https://wiki.sunet.se/display/SWAMID/SAML+WebSSO+Identity+Provider+Best+Current+Practice
            </a>.
          </p>
          <p>The Swamid best practice attribute release check consists of the following tests:</p>',
    'instructionsEntityCategoryEnd' => '<p>Multiple Code of Conduct test require different attributes which the IdP either SHOULD or SHOULD NOT
            release in accordance REFEDS/GÉANT Code of Conduct.</p>
          <p>For further information on how personal data is processed in SWAMID Best Practice Attribute Release
            check see
            <a href="https://wiki.sunet.se/display/SWAMID/SWAMID+Entity+Category+Release+Check+-+Privacy+Policy">
              https://wiki.sunet.se/display/SWAMID/SWAMID+Entity+Category+Release+Check+-+Privacy+Policy
            </a>
          </p>',
);
