<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used by
	| the validator class. Some of these rules have multiple versions such
	| as the size rules. Feel free to tweak each of these messages here.
	|
	*/

	"accepted"             => "The :attribute parameter must be accepted.",
	"active_url"           => "The :attribute parameter is not a valid URL.",
	"after"                => "The :attribute parameter must be a date after :date.",
	"alpha"                => "The :attribute parameter may only contain letters.",
	"alpha_dash"           => "The :attribute parameter may only contain letters, numbers, and dashes.",
	"alpha_num"            => "The :attribute parameter may only contain letters and numbers.",
	"array"                => "The :attribute parameter must be an array.",
	"before"               => "The :attribute parameter must be a date before :date.",
	"between"              => [
		"numeric" => "The :attribute parameter must be between :min and :max.",
		"file"    => "The :attribute parameter must be between :min and :max kilobytes.",
		"string"  => "The :attribute parameter must be between :min and :max characters.",
		"array"   => "The :attribute parameter must have between :min and :max items.",
	],
	"boolean"              => "The :attribute parameter must be true or false.",
	"confirmed"            => "The :attribute parameter confirmation does not match.",
	"date"                 => "The :attribute parameter is not a valid date.",
	"date_format"          => "The :attribute parameter does not match the format :format.",
	"different"            => "The :attribute parameter and :other must be different.",
	"digits"               => "The :attribute parameter must be :digits digits.",
	"digits_between"       => "The :attribute parameter must be between :min and :max digits.",
	"email"                => "The :attribute parameter must be a valid email address.",
	"filled"               => "The :attribute parameter is required.",
	"exists"               => "The given :attribute parameter is invalid.",
	"image"                => "The :attribute parameter must be an image.",
	"in"                   => "The given :attribute parameter is invalid.",
	"integer"              => "The :attribute parameter must be an integer.",
	"ip"                   => "The :attribute parameter must be a valid IP address.",
	"max"                  => [
		"numeric" => "The :attribute parameter may not be greater than :max.",
		"file"    => "The :attribute parameter may not be greater than :max kilobytes.",
		"string"  => "The :attribute parameter may not be greater than :max characters.",
		"array"   => "The :attribute parameter may not have more than :max items.",
	],
	"mimes"                => "The :attribute parameter must be a file of type: :values.",
	"min"                  => [
		"numeric" => "The :attribute parameter must be at least :min.",
		"file"    => "The :attribute parameter must be at least :min kilobytes.",
		"string"  => "The :attribute parameter must be at least :min characters.",
		"array"   => "The :attribute parameter must have at least :min items.",
	],
        "mongo_id"             => "The given :attribute is not a 12-byte BSON type.",
	"not_in"               => "The given :attribute parameter is invalid.",
	"numeric"              => "The :attribute parameter must be a number.",
	"regex"                => "The :attribute parameter format is invalid.",
	"required"             => "The :attribute parameter is required.",
	"required_if"          => "The :attribute parameter is required when :other is :value.",
	"required_with"        => "The :attribute parameter is required when :values is present.",
	"required_with_all"    => "The :attribute parameter is required when :values is present.",
	"required_without"     => "The :attribute parameter is required when :values is not present.",
	"required_without_all" => "The :attribute parameter is required when none of :values are present.",
	"same"                 => "The :attribute parameter and :other must match.",
	"size"                 => [
		"numeric" => "The :attribute parameter must be :size.",
		"file"    => "The :attribute parameter must be :size kilobytes.",
		"string"  => "The :attribute parameter must be :size characters.",
		"array"   => "The :attribute parameter must contain :size items.",
	],
	"unique"               => "The :attribute parameter has already been taken.",
	"url"                  => "The :attribute parameter format is invalid.",
	"timezone"             => "The :attribute parameter must be a valid zone.",

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute.rule" to name the lines. This makes it quick to
	| specify a specific custom language line for a given attribute rule.
	|
	*/

	'custom' => [
		'attribute-name' => [
			'rule-name' => 'custom-message',
		],
	],

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as E-Mail Address instead
	| of "email". This simply helps us make messages a little cleaner.
	|
	*/

	'attributes' => [],

];
