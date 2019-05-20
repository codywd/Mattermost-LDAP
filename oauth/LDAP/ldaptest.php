
<?php

error_reporting(E_ALL);

###################################---Configuration---################################

//Ldap adress and port
$hostname = "ldaps://domain.local";

//LDAP version
$ldap_version = 3;

//Unique identifier of user on LDAP
$uid = "user01";

//directory name (dn)
$dn = "CN=mattermost,OU=ServiceAccounts,OU=Benutzer,OU=company,DC=domain,DC=local";

//Password (Only for test, we give the password in clear text)
$pass = "ExamplePW";

//ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
$ldapconn = ldap_connect( $hostname )
              or die( "Cannot connect" );
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

$base = "OU=Users,DC=company,DC=local";
$bases = ["OU=Verwaltung,DC=company,DC=local","OU=Users,DC=company,DC=local","OU=Admins,DC=company,DC=local"];
######################################################################################
$filter = "(&(objectClass=user)(sAMAccountName=user01)(memberof=CN=Mattermost,OU=Berechtigungsgruppen,OU=Gruppen,OU=Users,DC=company,DC=local))";

echo "<h3>LDAP : Test Center</h3>";
echo "Attempting to connect LDAP server ... <br />";
$ldap=$ldapconn;
ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, $ldap_version);

if ($ldap) {
    echo "Successful connection ! <br />\r\n";
    echo "Checking LDAP credentials ... <br />\r\n";
    //ldap_start_tls($ldap);
    $is_valid=ldap_bind($ldap,$dn,$pass);

    if ($is_valid) {
        echo "Successful authentication ! <br />\r\n";
        echo "Getting user informations ...<br />\r\n";
        foreach ($bases as $base) {
            try {
                $result = $user_data=ldap_search($ldap, $base, $filter);
            } catch (Exception $result) {
                continue;
            }
            echo "this ->".$result."<-\r\n";
                $info_user = ldap_get_entries($ldap, $user_data);
                        for ($i=0; $i<$info_user["count"]; $i++) {
                echo "XOX.\r\n";
                        echo "dn: " . $info_user[$i]["dn"] . "<br />";
                        echo "cn: " . $info_user[$i]["cn"][0] . "<br />";
                        echo "title: " . $info_user[$i]["title"][0] . "<br />";
                        echo "email: " . $info_user[$i]["mail"][0] . "<br /><hr />";
                        echo "email: " . $info_user[$i]["division"][0] . "<br /><hr />";
                        }
        }
    } else {
                echo "Identification has failed ... Check your credentials<br /><br />";
        }
        echo "Closing LDAP connection.";
    ldap_close($ldap);
} else {
    echo "Impossible to connect to LDAP server !";
}
