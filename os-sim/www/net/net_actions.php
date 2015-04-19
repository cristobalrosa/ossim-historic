<?php
/**
 *
 * License:
 *
 * Copyright (c) 2003-2006 ossim.net
 * Copyright (c) 2007-2013 AlienVault
 * All rights reserved.
 *
 * This package is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 dated June, 1991.
 * You may not use, modify or distribute this program under any other version
 * of the GNU General Public License.
 *
 * This package is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this package; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston,
 * MA  02110-1301  USA
 *
 *
 * On Debian GNU/Linux systems, the complete text of the GNU General
 * Public License can be found in `/usr/share/common-licenses/GPL-2'.
 *
 * Otherwise you can read it here: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 */


//Config File
require_once 'av_init.php';

Session::logcheck('environment-menu', 'PolicyNetworks');

session_write_close();

//Validate action type

$action = POST('action');

ossim_valid($action, OSS_LETTER, '_', 'illegal:' . _('Action'));

if (ossim_error())
{
    $data['status'] = 'error';
    $data['data']   = ossim_get_error_clean();

    echo json_encode($data);
    exit();
}


//Validate Form token

$token = POST('token');

if (Token::verify('tk_net_form', POST('token')) == FALSE)
{
    $data['status'] = 'error';
    $data['data']   = Token::create_error_message();

    echo json_encode($data);
    exit();
}

switch ($action)
{
    case 'delete_net':

        $validate = array(
            'asset_id' => array('validation' => 'OSS_HEX', 'e_message' => 'illegal:' . _('Host ID'))
        );

        $net_id = POST('asset_id');

        $validation_errors = validate_form_fields('POST', $validate);

        $db   = new ossim_db();
        $conn = $db->connect();

        $can_i_modify_ips = Asset_net::can_i_modify_ips($conn, $net_id);

        $db->close();

        if ((is_array($validation_errors) && !empty($validation_errors)) || $can_i_modify_ips == FALSE)
        {
            $data['status'] = 'error';
            $data['data']   = _('Error! Net ID not allowed.  Net could not be removed');
        }
        else
        {
            try
            {
                $db   = new ossim_db();
                $conn = $db->connect();

                Asset_net::delete_from_db($conn, $net_id, TRUE);

                $db->close();

                $data['status'] = 'OK';
                $data['data']   = _('Net removed successfully');

            }
            catch (Exception $e)
            {
                $data['status'] = 'error';
                $data['data']   = _('Error! Net could not be removed');
            }
        }

        break;

    case 'remove_icon':

        $validate = array(
            'asset_id' => array('validation' => 'OSS_HEX', 'e_message' => 'illegal:' . _('Net ID'))
        );

        $net_id = POST('asset_id');

        $validation_errors = validate_form_fields('POST', $validate);


        if (is_array($validation_errors) && !empty($validation_errors))
        {
            $data['status'] = 'error';
            $data['data']   = _('Error! Net ID not allowed.  Icon could not be removed');
        }
        else
        {
            try
            {
                $db   = new ossim_db();
                $conn = $db->connect();

                Asset_net::delete_icon($conn, $net_id);

                $db->close();

                $data['status'] = 'OK';
                $data['data']   = _('Net icon removed successfully');

            }
            catch (Exception $e)
            {
                $data['status'] = 'error';
                $data['data']   = _('Error! Net icon could not be removed');
            }
        }

        break;
}

echo json_encode($data);
exit();
?>
