<?php
header("Content-type: text/css; charset: UTF-8");

$post_id = !empty($_GET["post_id"]) ? intval($_GET["post_id"]) : null;
$backup_id = $post_id;
$iron_music_event_style = new Dynamic_Styles('_iron_music_event_options');


/* New Event Styles */
$iron_music_event_style->setFont('.event-line-node', 'events_item_typography', true);
$iron_music_event_style->setColor('.event-line-wrap:hover .event-line-node', 'events_item_hover_text_color');
$iron_music_event_style->setBackgroundColor('a.event-line-wrap', 'events_item_bg_color');
$iron_music_event_style->setBackgroundColor('a.event-line-wrap:hover', 'events_item_hover_bg_color');
$iron_music_event_style->setFont('.countdown-block', 'events_countdown_typography', true);
$iron_music_event_style->set('.countdown-block', 'letter-spacing', 'events_countdown_letterspacing');
$iron_music_event_style->setBackgroundColor('a.event-line-wrap .event-line-countdown-wrap', 'events_countdown_bg_color');
$iron_music_event_style->set('a.event-line-wrap', 'padding-top', 'events_items_padding');
$iron_music_event_style->set('a.event-line-wrap', 'padding-bottom', 'events_items_padding');
$iron_music_event_style->set('ul.concerts-list', 'border-top-color', 'events_outline_colors');
$iron_music_event_style->set('ul.concerts-list li', 'border-bottom-color', 'events_outline_colors');
$iron_music_event_style->set('.events-bar', 'border-top-color', 'events_outline_colors');
$iron_music_event_style->set('span.events-bar-artists select', 'border-color', 'events_outline_colors');
$iron_music_event_style->setBackgroundColor('.events-bar', 'events_filter_bg_color');
$iron_music_event_style->setFont('span.events-bar-title, span.events-bar-artists select', 'events_filter_typography', true);
$iron_music_event_style->set('span.events-bar-title, span.events-bar-artists select', 'letter-spacing', 'events_filter_letterspacing');
$iron_music_event_style->set('span.events-bar-artists:after', 'border-top-color', 'events_outline_colors');
$iron_music_event_style->set('span.events-bar-artists:after', 'border-bottom-color', 'events_outline_colors');



$global_custom_css = $iron_music_event_style->get_option('custom_css');
$iron_music_event_style->setCustomCss($global_custom_css);

$iron_music_event_style->render();



$iron_music_music_style = new Dynamic_Styles('_iron_music_music_player_options');

// Music Player Style
$music_player_background = '.iron_widget_radio .info-box';
$iron_music_music_style->setBackgroundColor($music_player_background, 'music_player_background_color');

$music_player_text = '.player-title-box .title, .time-box, .jp-current-time, .jp-duration, .player-info .player-title-box';
$iron_music_music_style->setColor($music_player_text, 'music_player_text_color');

$music_player_icon = '.jp-play i, .jp-pause i, .jp-previous i, .jp-next i';
$iron_music_music_style->setColor($music_player_icon, 'music_player_icon_color');
$music_player_icon_hover = '.jp-play i:hover, .jp-pause i:hover, .jp-previous i:hover, .jp-next i:hover';
$iron_music_music_style->setColor($music_player_icon_hover, 'music_player_background_color');

$music_player_hover = '.jp-play i:hover, .jp-pause i:hover, .jp-previous i:hover, .jp-next i:hover';
$iron_music_music_style->setBackgroundColor($music_player_hover, 'music_player_hover_color');

$music_player_timeline = '.player-box .jp-seek-bar';
$iron_music_music_style->setBackgroundColor($music_player_timeline, 'music_player_timeline_color');

$music_player_progress = '.player-box .jp-seek-bar .jp-play-bar';
$iron_music_music_style->setBackgroundColor($music_player_progress, 'music_player_progress_color');

$music_player_playlist = '.audio-holder .jp-playlist ul li';
$iron_music_music_style->setBackgroundColor($music_player_playlist, 'music_player_playlist_background_color');

$music_player_hover_playlist = '.jp-playlist ul.tracks-list li:hover';
$iron_music_music_style->setBackgroundColor($music_player_hover_playlist, 'music_player_playlist_hover_color');

$music_player_playlist_splitter = '.iron_widget_radio .player-box ul.jp-controls, .audio-holder .jp-playlist ul.tracks-list li, .player-box .jp-controls li a i, .player-box .jp-controls li a i.fa-backward, .player-box .jp-controls li a i.fa-forward, .iron_widget_radio ul.jp-controls, .iron_widget_radio, .iron_widget_radio ul.jp-controls';
$iron_music_music_style->setBorderColor($music_player_playlist_splitter, 'music_player_playlist_splitter_color');

$music_player_playlist_active_text_color = 'a.jp-playlist-item.jp-playlist-current span.track-name, .audio-holder .jp-playlist ul.tracks-list li.jp-playlist-current::before';
$iron_music_music_style->setColor($music_player_playlist_active_text_color, 'music_player_playlist_active_text_color');

$global_custom_css = $iron_music_music_style->get_option('custom_css');
$iron_music_music_style->setCustomCss($global_custom_css);

$iron_music_music_style->render();