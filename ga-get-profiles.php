<?php

include_once('modules/config.php');

if(!loggedIn()):
    header('Location: login.php');
    exit();
else:
    $query = $coll->findOne(array('username' => $_SESSION["username"]));
    $refresh_token = $query['ga_refresh_token'];

    if (!isset($query['ga_refresh_token'])):
        echo "No Google Analytics refresh token was found.";
        header('Location: members.php');
        exit();
    else:
        //Get access token using refresh token
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'refresh_token='.$refresh_token.'&client_id='.$client_id.'&client_secret='.$client_secret.'&grant_type=refresh_token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($data, true);
        $access_token = $result['access_token'];

        //Get web properties for account
        if(isset($access_token)):
            $url = 'https://www.googleapis.com/analytics/v3/management/accounts/~all/webproperties/~all/profiles?key='.$api_key;
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $data = curl_exec($ch);
            curl_close($ch);
            $result_properties = json_decode($data, true);

            foreach ($result_properties['items'] as $obj_add):
                $dropdown_add .= "<option value='" . $obj_add['id'] . "*" . $obj_add['name'] . "'>" . $obj_add['name'] . "</option>";
            endforeach;

            foreach ($query['ga_web_property'] as $obj_delete):
                $dropdown_delete .= "<option value='" . $obj_delete['ga_property_id'] . "'>" . $obj_delete['ga_property_name'] . "</option>";
            endforeach;
        endif;
    endif;

    if(isset($_POST['property_add'])):
        $try = explode('*',$_POST['property_add']);
        $ga_property_id = $try[0];
        $ga_property_name = $try[1];

        getWebProperty($query['username'], $ga_property_id, $ga_property_name);
        echo '<script>parent.window.location.reload(true);</script>';
    endif;

    if(isset($_POST['property_delete'])):
        $ga_property_id = $_POST['property_delete'];

        deleteWebProperty($query['username'], $ga_property_id);
        echo '<script>parent.window.location.reload(true);</script>';
    endif;
endif;

?>

<html>
<head>
    <title>Select a Web Property</title>
</head>
<body>
<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
    <table>
        <tr>
            <td>
                Select a Web Property:
            </td>
        </tr>
        <tr>
            <td>
                <select name="property_add">
                    <?php echo $dropdown_add;?>
                </select>
            </td>
            <td>
                <input name="submit_select" type="submit" value="Add Property">
                </select>
            </td>
        </tr>
    </table>
</form>
<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
    <table>
        <tr>
            <td>
                Delete a Web Property:
            </td>
        </tr>
        <tr>
            <td>
                <select name="property_delete">
                    <?php echo $dropdown_delete;?>
                </select>
            </td>
            <td>
                <input name="submit_delete" type="submit" value="Delete Property">
                </select>
            </td>
        </tr>
    </table>
</form>
</body>
</html>