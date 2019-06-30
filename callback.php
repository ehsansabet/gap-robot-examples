<?php
/**
 * Author: Ehsan Sabet (ehsan.sabet@hotmail.com)
 */

require dirname( __FILE__ ) . '/vendor/autoload.php';

use Gap\SDP\Api;
use ehsansabet\GapUtils\InlineCalendar;

$token = {YOUR-TOKEN};
try {
	$gap = new Api( $token );
} catch ( Exception $e ) {
	throw new \Exception( 'an error was encountered' );
}

$data    = isset( $_POST['data'] ) ? $_POST['data'] : null;
$type    = isset( $_POST['type'] ) ? $_POST['type'] : null;
$chat_id = isset( $_POST['chat_id'] ) ? $_POST['chat_id'] : null;
$from    = isset( $_POST['from'] ) ? $_POST['from'] : null;

if ( $data == '/calendar' ) {
	$InlineKeyboard = InlineCalendar::show();

	return $gap->sendText( $chat_id, 'تقویم', null, $InlineKeyboard );
}

if ($type == 'triggerButton') {
	$trigger = json_decode($data);
	$btnData = explode('-', $trigger->data);

	if ($trigger->data == 'null') {
		return $gap->answerCallback($chat_id, $trigger->callback_id, 'none', false);
	}

	if (!empty($btnData[0]) && !empty($btnData[1]) && !empty($btnData[2])) {
		return $gap->answerCallback($chat_id, $trigger->callback_id, $trigger->data, true);
	}

	$calendarConfig = [
		'format' => $trigger->data
	];
	$InlineKeyboard = InlineCalendar::show($calendarConfig);
	try {
		return $gap->editMessage($chat_id, (int) $trigger->message_id, null, $InlineKeyboard);
	} catch (Exception $e) {
		return;
	}
}

return $gap->sendText( $chat_id, 'برای نمایش تقویم /calendar را ارسال کنید.'