<?php

foreach (parse_ini_file('env.ini') as $key => $value) {
  define($key, $value);
}