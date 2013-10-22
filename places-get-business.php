<?php

include_once('modules/config.php');

if(!loggedIn()):
    header('Location: login.php');
    exit();
else:
    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    foreach ($query['places_business'] as $obj_delete):
        $dropdown_delete .= "<option value='" . $obj_delete['places_id'] . "'>" . $obj_delete['places_name'] . "</option>";
    endforeach;

    if(isset($_POST["search"])):
        // First check that required fields have been filled in.
        if (empty($_POST['location'])):
            $errors['location'] = "Location cannot be empty.";
        endif;
        if (empty($_POST['business'])):
            $errors['business'] = "Business cannot be empty.";
        endif;
    endif;

    if (isset($_POST["search"]) && empty($errors)):
        $location = str_replace(" ", "+", $_POST["location"]);
        $business = str_replace(" ", "+", $_POST["business"]);

        $result_geocode = makeGeolocationAPIRequest($location);
        $result_places = makePlacesAPIRequestSearch($result_geocode, $business);

        foreach ($result_places['results'] as $obj_add):
            $dropdown_add .= "<option value='" . $obj_add['reference'] . "*" . $obj_add['name'] . "'>" . $obj_add['name'] . "--" . $obj_add['vicinity'] ."</option>";
        endforeach;
    endif;

    if(isset($_POST['submit_add'])):
        $try = explode('*',$_POST['business_add']);
        $places_id = $try[0];
        $places_name = $try[1];
        $try2 = explode('--',$places_name);
        $places_name = $try2[0];

        getPlacesBusiness($query['username'], $places_id, $places_name);
        echo '<script>parent.window.location.reload(true);</script>';
    endif;

    if(isset($_POST['submit_delete'])):
        $places_id = $_POST['business_delete'];

        deletePlacesBusiness($query['username'], $places_id);
        echo '<script>parent.window.location.reload(true);</script>';
    endif;
endif;

?>

<html>
<head>
    <title>Search Google Places for a Business</title>
</head>
<body>
<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
    <table>
        <tr>
            <td>
                Location:
            </td>
            <td>
                <input type="text" name="location" value="<?php print isset($_POST["location"]) ? $_POST["location"] : "" ; ?>" maxlength="50">
                    <span class="error">
                        <?php echo isset($errors['location']) ? $errors['location'] : ''; ?>
                    </span><br />
            </td>
        </tr>
        <tr>
            <td>
                Business:
            </td>
            <td>
                <input type="text" name="business" value="<?php print isset($_POST["business"]) ? $_POST["business"] : "" ; ?>" maxlength="100">
                    <span class="error">
                        <?php echo isset($errors['business']) ? $errors['business'] : ''; ?>
                    </span><br />
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td>
                <input name="search" type="submit" value="Search">
            </td>
        </tr>
    </table>
</form>
<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
    <table>
        <tr>
            <td>
                Select a Business:
            </td>
        </tr>
        <tr>
            <td>
                <select name="business_add">
                    <?php echo $dropdown_add;?>
                </select>
            </td>
            <td>
                <input name="submit_add" type="submit" value="Add Business">
                </select>
            </td>
        </tr>
    </table>
</form>
<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
    <table>
        <tr>
            <td>
                Delete a Business:
            </td>
        </tr>
        <tr>
            <td>
                <select name="business_delete">
                    <?php echo $dropdown_delete;?>
                </select>
            </td>
            <td>
                <input name="submit_delete" type="submit" value="Delete Business">
                </select>
            </td>
        </tr>
    </table>
</form>
</body>
</html>