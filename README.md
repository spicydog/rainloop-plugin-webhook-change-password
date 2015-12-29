# Webhook Change Password (Rain Loop Plugin)
A password change plugin for RainLoop Webmail using POST request to a password change URL.

## Installation
1. Clone the project to plugin directory (data/_data_/_default_/plugins)
2. Enable plugin at admin consolr (RAINLOOP_URL?admin#/plugins)
3. Config URL for password change file (should be in HTTPS for security purpose)
4. Create or modify password change file that has been point recently to accept POST request with parameters "username", "old_password", and "new_password".
5. Return the request with JSON, if the changing process is success, return code 200, otherwise means error.
6. Test is it work or modify plugin as you wish.

## Example Code for Password Changer
    <?php
    
    $output = [];
    if (!empty($_POST['username']) &&
      !empty($_POST['old_password']) &&
      !empty($_POST['new_password'])) {
    
      $status     =   [];
      $status[0]  =  "The password was modified successfully";
      $status[2]  =  "Missing new password";
      $status[3]  =  "Missing current password";
    
      $result = changePassword($_POST['username'], $_POST['old_password'], $_POST['new_password']);
    
      $output['code'] = $result;
      if ($result === 0) {
        $output['code'] = 200;
      }
    
      if (isset($status[$result])) {
          $msg = $status[$result];
        } else {
          $msg = 'An error has occurred while attempting to change your password.';
      }
      $output['msg']  = $msg;
    } else {
      $output['code'] = 400;
      $output['msg']  = 'Missing field';
    }
    
    header('Content-Type: application/json');
    echo json_encode($output);
    
    function changePassword($username, $currentPassword, $newPassword) {
    
      // Password chaning process here
      
      // Return 0 for success status
      $status = 0;
    
      return $status;
    }

## Acknowledgement
This plug-in is based on **poppassd-change-password** which is available here: https://github.com/RainLoop/rainloop-webmail/tree/master/plugins/poppassd-change-password.