<?php

if (!empty($_REQUEST["action"])) {
    if ($_REQUEST["action"] == "edit" || $_REQUEST["action"] == "add" || $_REQUEST["action"] == "delete" || $_REQUEST["action"] == "view") {
        if ($_REQUEST["action"] == "edit" && !$is_edit_enabled) {
            $_SESSION["cms_status"] = "error";
            $_SESSION["cms_msg"] = "Edit action disabled!";
            header('Location:' . $current_page . '');
            exit();
        }
        if ($_REQUEST["action"] == "add" && !$is_add_enabled) {
            $_SESSION["cms_status"] = "error";
            $_SESSION["cms_msg"] = "Add action disabled!";
            header('Location:' . $current_page . '');
            exit();
        }
        if ($_REQUEST["action"] == "delete" && !$is_delete_enabled) {
            $_SESSION["cms_status"] = "error";
            $_SESSION["cms_msg"] = "Delete action disabled!";
            header('Location:' . $current_page . '');
            exit();
        }

        if ($_REQUEST["action"] == "delete" && $is_delete_enabled && !empty($_REQUEST["product_id"])) {
            $sql = "DELETE FROM $table_name WHERE `product_id`='" . $db_cms->removeQuote($_REQUEST["product_id"]) . "'";
            $res = $db_cms->delete_query($sql);
            if ($res != FALSE) {
                $_SESSION["cms_status"] = "success";
                $_SESSION["cms_msg"] = "Deleted successfully!";
                header('Location:' . $current_page . '');
                exit();
            } else {
                $_SESSION["cms_status"] = "error";
                $_SESSION["cms_msg"] = "Unable to delete!";
                header('Location:' . $current_page . '');
                exit();
            }
        }
    } else {
        header('Location:' . $current_page . '');
        exit();
    }
}
