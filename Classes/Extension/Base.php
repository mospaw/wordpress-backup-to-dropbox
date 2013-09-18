<?php
/**
 * @copyright Copyright (C) 2011-2013 Michael De Wildt. All rights reserved.
 * @author Michael De Wildt (http://www.mikeyd.com.au/)
 * @license This program is free software; you can redistribute it and/or modify
 *          it under the terms of the GNU General Public License as published by
 *          the Free Software Foundation; either version 2 of the License, or
 *          (at your option) any later version.
 *
 *          This program is distributed in the hope that it will be useful,
 *          but WITHOUT ANY WARRANTY; without even the implied warranty of
 *          MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *          GNU General Public License for more details.
 *
 *          You should have received a copy of the GNU General Public License
 *          along with this program; if not, write to the Free Software
 *          Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110, USA.
 */
abstract class WPB2D_Extension_Base
{
    const TYPE_DEFAULT = 1;
    const TYPE_OUTPUT = 2;

    protected
        $dropbox,
        $dropbox_path,
        $config
        ;

    private $chunked_upload_threashold;

    public function __construct()
    {
        $this->dropbox = WPB2D_Factory::get('dropbox');
        $this->config  = WPB2D_Factory::get('config');
    }

    public function set_chunked_upload_threashold($threashold)
    {
        $this->chunked_upload_threashold = $threashold;

        return $this;
    }

    public function get_chunked_upload_threashold()
    {
        if ($this->chunked_upload_threashold !== null)
            return $this->chunked_upload_threashold;

        return CHUNKED_UPLOAD_THREASHOLD;
    }

    public function update($data) {
        $exclude = array(
            'wpb2d_save_changes',
            '_wpnonce',
            '_wp_http_referer',
        );

        foreach ($data as $key => $value) {
            if ($value == 'on') {
                $value = true;
            } elseif ($value == 'off') {
                $value = false;
            }

            if (in_array($key, $exclude)) {
                continue;
            }

            //Validate option will throw an exception if the value is invalid
            $this->validate_option($key, $value);

            $this->config->set_option($key, $value);
        }
    }

    abstract public function start();
    abstract public function complete();
    abstract public function failure();

    abstract public function get_form_items();
    abstract public function get_menu_title();
    abstract public function get_type();

    abstract public function is_enabled();

    abstract protected function validate_option($key, $value);
}
