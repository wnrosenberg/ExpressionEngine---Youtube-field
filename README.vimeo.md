
#Vimeo Fieldtype

A fieldtype for parsing Youtube links, grabbing the Youtube video ID and then allowing a user different ways to embed those videos within their site.

***

##Installation

###Simple Installation

Simply copy the pi.surgeree.php file into a directory called surgeree inside the third_party folder of your ExpressionEngine installation.

###Fancy Installation (for git users)

Make this repo into a submodule for your project's private ExpressionEngine git repo.

	git submodule add git://github.com/dsurgeons/SurgerEE.git system/expressionengine/third_party/surgeree

For added flexiblity, fork this repo and make the submodule to your fork instead. Don't forget to submit pull requests after you've added stuff :).

***

##Usage

Display video ID:

	{custom_field_name display='id'}

	<iframe title="Vimeo video player" width="xxx" height="xxx" src="http://player.vimeo.com/video/{custom_field_name display='id'}" frameborder="0" allowfullscreen></iframe>

Display video URL: http://vimeo.com/VIDEOID

	{custom_field_name} (Default behavior)
	{custom_field_name display='url'}

Display vimeo embed code:

	{custom_field_name display='embed' width='xxx' height='xxx'}

This will default to default width and height set in field settings.

Pass url parameters:

	{custom_field_name display='embed' url_params="wmode=transparent"}

Enable the Javascript API: 

	{custom_field_name display='embed' froog='1'[ player_id='my_player_id']}
	If omitted, player_id will be set to the value of microtime().

***

##Changelog

###1.0
Forked from [@fideloper](https://github.com/fideloper)'s [Youtube Fieldtype](https://github.com/fideloper/ExpressionEngine---Youtube-field) and initial edits made by [@wnrosenberg](https://github.com/wnrosenberg), javascript player API support added.



