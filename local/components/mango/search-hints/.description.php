<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("SEARCH_FILTER_TAGS"),
	"DESCRIPTION" => GetMessage("SEARCH_FILTER_TAGS"),
	"ICON" => "/images/menu_ext.gif",
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "utility",
		"CHILD" => array(
			"ID" => "search",
			"NAME" => GetMessage("SEARCH_FILTER_TAGS")
		)
	),
);

?>