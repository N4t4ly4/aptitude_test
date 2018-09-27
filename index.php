<!--
    Author: Natalya Despain
    Date: 9/24/2018
    Description:This is a small serverside program that retrieves linkedIn statuses and status metadata from a company on linkedin. The user that logs in must be the company admin.
-->

<!--set up variables-->
<?php
//error_reporting(0);
//variable for retrieved authorization code
$code = filter_input(INPUT_GET, "code");
//variable for state parameter to prevent CSRF
$state = filter_input(INPUT_GET, "state");
//variable for URL for API call
$url = 'https://api.linkedin.com/v1/companies/18827867/updates?event-type=status-update';
//variable for access token retrieved from linked in
$access_token = 1;
//variable to validate that we have retrieved an access token
$authentication_validator = 0;
//set variable for what we want to display from our LinkedIn API retrieval
$user_data = '';
//variable to store retrieved company id
$company_id = null;
?>

<!--HTML to mark up results-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LinkedIn Data</title>
</head>
<body>
    <h1>Retrieve Your LinkedIn Data:</h1>
    <!--
        The below link sends the actual http request to the linkedIn API to have the user log in and get an authorization code. 
    -->
    <a href="https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=86zjbkfwdh4bee&redirect_uri=http://localhost/aptitude_test/index.php&state=521">Log in</a>
    <div>
        <?php
                //ensure state was equal to what I set it as to prevent CSRF
                if($state = 521) {
                    //ensure we got an authorization code
                    if(isset($code)){
                        //the below block makes an http request to linkedIn to retrieve an access token with are already retrieved access code.

                        //initialize curl session used to make an http request
                        $ch = curl_init();
                        //set url
                        curl_setopt($ch, CURLOPT_URL,"https://www.linkedin.com/oauth/v2/accessToken");
                        //set http request type
                        curl_setopt($ch, CURLOPT_POST, 0);
                        //set parameters for the request
                        curl_setopt($ch, CURLOPT_POSTFIELDS,"grant_type=authorization_code&code=".$code.
                        "&redirect_uri=http://localhost/aptitude_test/index.php&client_id=86zjbkfwdh4bee&client_secret=baPHaE22GDzehicR");
                        //set return value as a string
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        //set respone of http request to $access_token
                        $access_token = curl_exec($ch);
                        //close curl session
                        curl_close($ch);
                        //set authentication_validator so we know we have an access token
                        $authentication_validator = 1;
                        
                    }
                }
        
                //ensure we have an access code, and valid access token
                if(isset($code) && $authentication_validator = 1 && json_decode($access_token)->access_token != ''){
                    //change access_token to JSON format
                    $access_token_value = json_decode($access_token)->access_token;

                    //initialize curl session to make request to LinkedIn API now that we have an access token
                    $ch = curl_init();
                    //link for actual http request to API
                    curl_setopt($ch, CURLOPT_URL, $url."&oauth2_access_token=".$access_token_value."&format=json");
                    //set http request type
                    curl_setopt($ch, CURLOPT_POST, 0);
                    //set return value as a string
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    //set a result variable to hold result of our http request
                    $result = curl_exec($ch);
                    //close curl session
                    curl_close ($ch);

                
                }
            
              
                //print what the request returned
                print_r($result);
                  
            
        ?>
        
        
    </div>
</body>
</html>


