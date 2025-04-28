<?php
if (in_array($callingFrom, ['header-menu', 'header-page-menu'])) {
	return standaloneMenuItems($callingFrom, 5);
}

standaloneMenus1And2();
