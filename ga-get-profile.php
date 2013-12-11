<?php

include_once('modules/config.php');

if(!loggedIn()):
    echo '<script> window.location="login.php"; </script> ';
else:
    $query = $coll->findOne(array('username' => $_SESSION["username"]));
    $refresh_token = $query['ga_refresh_token'];

    if (!isset($query['ga_refresh_token'])):
        echo '<script> window.location="' . $get_ga_code_url . '"; </script> ';
    else:
        $access_token = getAccessToken($refresh_token);

        //Get web properties for account
        if(isset($access_token)):
            $result_properties = getWebProperties($access_token);

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

        setWebProperty($query['username'], $ga_property_id, $ga_property_name);
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