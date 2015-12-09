<?php
echo "HTTP/1.1 302 REDIRECT\n";
echo "Cache-Control: no-cache, must-revalidate\n";
echo "Location: /CMD_PLUGINS/da_letsencrypt/" . $path;