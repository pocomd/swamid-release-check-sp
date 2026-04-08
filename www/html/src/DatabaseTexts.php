<?php
#IdPCheck.php:239: #status_ERROR
_('The IDP has sent too many attributes.');
#IdPCheck.php:252: #status_ERROR
_('Received multi-value for %s, should be single-value!', $key);
#IdPCheck.php:254: status_WARNING
_('The IDP has not sent all the expected attributes. See the comments below.');
#IdPCheck.php:290: status_OK
_('Did not send any attributes that were not requested.');
#IdPCheck.php:292: testResult
_('Did not send any attributes that were not requested.');

#IdPCheck.php:388: #status_ERROR
_('Received multi-value for %s, should be single-value!', $key);

#IdPCheck.php:562: status_WARNING
_('R&S requires displayName or givenName + sn.');
#IdPCheck.php:568: status_WARNING
_('R&S requires mail.');
#IdPCheck.php:572: status_WARNING
_('R&S requires eduPersonPrincipalName.');
#IdPCheck.php:575: status_OK
_('All the attributes required to fulfil R&S were sent');
#IdPCheck.php:577: testResult
_('R&S attributes OK, Entity Category Support OK');
#IdPCheck.php:579: testResult
_('R&S attributes OK, Entity Category Support missing');
#IdPCheck.php:580: status_WARNING
_("The IdP supports R&S but doesn't announce it in its metadata.");
#IdPCheck.php:581: status_WARNING
#IdPCheck.php:582: status_WARNING
_("Please add 'http://refeds.org/category/research-and-scholarship' to the list of supported ECs in Metadata");
#IdPCheck.php:587: testResult
_('R&S attributes missing, BUT Entity Category Support claimed');
#IdPCheck.php:590: testResult
_('R&S attribute missing, Entity Category Support missing');
#IdPCheck.php:596: #status_ERROR
_('The IdP does NOT support R&S but it claims that it does in its metadata!!');

#IdPCheck.php:609: status_WARNING
_('Anonymous requires schacHomeOrganization.');
#IdPCheck.php:614: status_WARNING
_('Anonymous requires eduPersonScopedAffiliation.');
#IdPCheck.php:618: status_OK
_('All the attributes required to fulfil Anonymous were sent');
#IdPCheck.php:620: testResult
_('Anonymous attributes OK, Entity Category Support OK');
#IdPCheck.php:622: testResult
_('Anonymous attributes OK, Entity Category Support missing');
#IdPCheck.php:623: status_WARNING
_("The IdP supports Anonymous but doesn't announce it in its metadata");
#IdPCheck.php:623: status_WARNING
#IdPCheck.php:624: status_WARNING
_("Please add 'https://refeds.org/category/anonymous' to the list of supported ECs in Metadata");
#IdPCheck.php:630: testResult
_('Anonymous attributes missing, BUT Entity Category Support claimed');
#IdPCheck.php:633: testResult
_('Anonymous attribute missing, Entity Category Support missing');
#IdPCheck.php:639: #status_ERROR
_('The IdP does NOT support Anonymous but it claims that it does in its metadata!!');

#IdPCheck.php:651: status_WARNING
_('Pseudonymous requires eduPersonAssurance.');
#IdPCheck.php:670: status_WARNING
#IdPCheck.php:745: status_WARNING
_('[[FED_NAME]] recommends that eduPersonAssurance contains https://refeds.org/assurance/IAP/low');
_('[[FED_NAME]] recommends that eduPersonAssurance contains https://refeds.org/assurance/ID/unique');
_('[[FED_NAME]] recommends that eduPersonAssurance contains https://refeds.org/assurance/ID/eppn-unique-no-reassign');
_('[[FED_NAME]] recommends that eduPersonAssurance contains https://refeds.org/assurance/ATP/ePA-1m');
#IdPCheck.php:675: status_WARNING
_('Pseudonymous requires that eduPersonAssurance at least contains https://refeds.org/assurance');
#IdPCheck.php:680: status_WARNING
_('Pseudonymous requires pairwise-id.');
#IdPCheck.php:685: status_WARNING
_('Pseudonymous requires schacHomeOrganization.');
#IdPCheck.php:690: status_WARNING
_('Pseudonymous requires eduPersonScopedAffiliation.');
#IdPCheck.php:694: status_OK
_('All the attributes required to fulfil Pseudonymous were sent');
#IdPCheck.php:696: testResult
_('Pseudonymous attributes OK, Entity Category Support OK');
#IdPCheck.php:698: testResult
_('Pseudonymous attributes OK, Entity Category Support missing');
#IdPCheck.php:699: status_WARNING
_("The IdP supports Pseudonymous but doesn't announce it in its metadata.");
#IdPCheck.php:700: status_WARNING
_("Please add 'https://refeds.org/category/pseudonymous' to the list of supported ECs in Metadata");
#IdPCheck.php:705: testResult
_('Pseudonymous attributes missing, BUT Entity Category Support claimed');
#IdPCheck.php:708: testResult
_('Pseudonymous attribute missing, Entity Category Support missing');

#IdPCheck.php:726: status_WARNING
_('Personalized requires eduPersonAssurance.');
#IdPCheck.php:713: #status_ERROR
_('The IdP does NOT support Pseudonymous but it claims that it does in its metadata!!');
#IdPCheck.php:750: status_WARNING
_('Personalized requires that eduPersonAssurance at least contains https://refeds.org/assurance');
#IdPCheck.php:756: status_WARNING
_('Personalized requires displayName, givenName and sn.');
#IdPCheck.php:761: status_WARNING
_('Personalized requires mail.');
#IdPCheck.php:766: status_WARNING
_('Personalized requires subject-id.');
#IdPCheck.php:771: status_WARNING
_('Personalized requires schacHomeOrganization.');
#IdPCheck.php:776: status_WARNING
_('Personalized requires eduPersonScopedAffiliation.');
#IdPCheck.php:780: status_OK
_('All the attributes required to fulfil Personalized were sent');
#IdPCheck.php:782: testResult
_('Personalized attributes OK, Entity Category Support OK');
#IdPCheck.php:784: testResult
_('Personalized attributes OK, Entity Category Support missing');
#IdPCheck.php:785: status_WARNING
_("The IdP supports Personalized but doesn't announce it in its metadata.");
#IdPCheck.php:786: status_WARNING
_("Please add 'https://refeds.org/category/personalized' to the list of supported ECs in Metadata");
#IdPCheck.php:791: testResult
_('Personalized attributes missing, BUT Entity Category Support claimed');
#IdPCheck.php:794: testResult
_('Personalized attribute missing, Entity Category Support missing');

#IdPCheck.php:798: #status_ERROR
_('The IdP does NOT support Personalized but it claims that it does in its metadata!!');
#IdPCheck.php:819: #status_ERROR
_('The IDP has not sent any attributes.');
#IdPCheck.php:821: #status_ERROR
_('The IDP has only sent %d attributes.');
#IdPCheck.php:832: status_OK
_('Fulfils Code of Conduct<br>');
#IdPCheck.php:834: testResult
_('CoCo OK, Entity Category Support OK');
#IdPCheck.php:836: testResult
_('CoCo OK, Entity Category Support missing');
#IdPCheck.php:837: status_WARNING
_("The IdP supports CoCo but doesn't announce it in its metadata.");
#IdPCheck.php:838: status_WARNING
_("Please add 'http://www.geant.net/uri/dataprotection-code-of-conduct/v1' to the list of supported ECs in Metadata");
_("Please add 'https://refeds.org/category/code-of-conduct/v2' to the list of supported ECs in Metadata");
#IdPCheck.php:843: testResult
_('CoCo is not supported, BUT Entity Category Support is claimed');
#IdPCheck.php:846: testResult
_('Support for CoCo missing, Entity Category Support missing');
#IdPCheck.php:850: #status_ERROR
_('The IdP does NOT support CoCo but it claims that it does in its metadata!!');

#IdPCheck.php:864: testResult
#IdPCheck.php:867: status_WARNING
_('schacPersonalUniqueCode in wrong case. Not urn:schac:personalUniqueCode:int:esi. Might create problem in some SP:s');
#IdPCheck.php:879: status_WARNING
_('schacPersonalUniqueCode should only contain <b>one</b> value.');
#IdPCheck.php:885: testResult
_('schacPersonalUniqueCode OK');
#IdPCheck.php:870: testResult
_('schacPersonalUniqueCode OK. BUT wrong case');
#IdPCheck.php:874: testResult
_('schacPersonalUniqueCode not starting with urn:schac:personalUniqueCode:int:esi:');
#IdPCheck.php:879: #status_ERROR
_('schacPersonalUniqueCode should start with urn:schac:personalUniqueCode:int:esi:');
#IdPCheck.php:881: testResult
_('More than one schacPersonalUniqueCode');
#IdPCheck.php:888: testResult
_('Missing schacPersonalUniqueCode');

#IdPCheck.php:1002: testResult
_('Sends invalid Assurance information.');
#IdPCheck.php:1005: testResult
_('Missing https://refeds.org/assurance for user.');
#IdPCheck.php:1007: status_WARNING
#IdPCheck.php:1008: testResult
_('Missing some Assurance information.');
#IdPCheck.php:1007: #status_ERROR
_('Identity Provider is sending invalid Assurance information.');
#IdPCheck.php:1010: #status_ERROR
_('Missing Assurance information. Expected at least https://refeds.org/assurance');
#IdPCheck.php:1010: status_OK
_("Assurance attribute release for current user follows REFED's recommendations.");
#IdPCheck.php:1011: testResult
_('Sends recommended Assurance information.');

#IdPCheck.php:1048: #status_ERROR
_("Authentication-instant hasn't updated after forceAuthn was requested.");
#IdPCheck.php:1079: status_OK
_('Identity Provider supports %s and ForceAuthn.');
#IdPCheck.php:1080: testResult
_('Supports REFEDS MFA and ForceAuthn.');
#IdPCheck.php:1083: testResult
_('Supports REFEDS MFA but not ForceAuthn.');
#IdPCheck.php:1085: status_OK
_('Identity Provider supports %s.');
#IdPCheck.php:1086: testResult
_('Supports REFEDS MFA.');
#IdPCheck.php:1088: #status_ERROR
_('Identity Provider supports %s but not ForceAuthn.');
#IdPCheck.php:1091: testResult
_('Does support ForceAuthn but not REFEDS MFA.');
#IdPCheck.php:1094: testResult
_('Does neither support REFEDS MFA or ForceAuthn.');
#IdPCheck.php:1094: testResult
_('Does not support REFEDS MFA.');
#IdPCheck.php:1096: #status_ERROR
_('Identity Provider does support ForceAuthn but not %s.');
#IdPCheck.php:1099: #status_ERROR
_('Identity Provider does neither support %s or ForceAuthn.');
#IdPCheck.php:1102: #status_ERROR
_('Identity Provider does not support %s.');
