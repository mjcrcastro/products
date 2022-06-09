<?php


    function userCan($action_code, $user) {
        /*
         * Will check for all the actions assigned to the role the user
         * has been assigned to. In case there is at leas one 
         * permission that corresponds to the action_code, the user
         * has been granted permissions, otherwise the answer is false
         */
        return null; //temporary until I fix the roles and permissions
        $actions_allowed = $user->role->actions()->where('code', '=', $action_code)->get();

        return count($actions_allowed) ? 0 : 'Access denied to action : ' . actionDescription($action_code);
    }
