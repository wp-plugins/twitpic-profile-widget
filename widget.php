<?php
/**
 * Plugin Name: Twitpic Profile Widget
 * Plugin URI: https://code.google.com/p/world-travel-blog
 * Description: This widget will display your Twitpic Profile in the widget area displaying your latest twitpics.
 * Version: 1.0
 * Author: Peter Rosanelli
 * Author URI: http://www.worldtravelblog.com
 */

/**
* LICENSE
* This file is part of Twitpic Profile Widget.
*
* Google Latitude Badge Widget is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*
* @package    twitpic-profile-widget
* @author     Peter Rosanelli <peter@worldtravelblog.com>
* @copyright  Copyright 2011 Peter Rosanelli
* @license    http://www.gnu.org/licenses/gpl.txt GPL 2.0
* @version    1.0
* @link       https://code.google.com/p/world-travel-blog
*/

add_action( 'widgets_init', 'twitpic_profile_widget' );
define(MAX_IMAGES, 20); // defined by Twitpic

function twitpic_profile_widget() {
	register_widget( 'Twitpic_Profile_Widget' );
}

/**
 * Twitpic Profile Widget
 */
class Twitpic_Profile_Widget extends WP_Widget {
	

	/** constructor */
	function Twitpic_Profile_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'twitpic_profile', 'description' => 'A widget for displaying your latest photos in your twitpic account' );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'twitpic-profile-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'twitpic-profile-widget', 'Twitpic Profile Widget', $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );

		/* Before widget (defined by themes). */	
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		echo '<script type="text/javascript" src="http://widgets.twitpic.com/j/2/widget.js?username='.$instance['twitter_username'].
			'&colorbg='.$instance['background_color'].
			'&colorborder='.$instance['border_color'].
			'&colorlinks='.$instance['link_color'].
			'&colortweetbg='.$instance['tweet_background_color'].
			'&colortweets='.$instance['tweet_text_color'].
			'&theme='.$instance['theme'].
			'&width='.$instance['width'].
			'&count='.$instance['number_of_images'].
			'&timestamp='.$instance['show_timestamps'].'"></script>';
		
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		
		$instance['twitter_username'] = strip_tags( $new_instance['twitter_username'] );
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['theme'] = strip_tags( $new_instance['theme'] );
		$instance['background_color'] = strip_tags( $new_instance['background_color'] );
		$instance['border_color'] = strip_tags( $new_instance['border_color'] );
		$instance['link_color'] = strip_tags( $new_instance['link_color'] );
		$instance['tweet_background_color'] = strip_tags( $new_instance['tweet_background_color'] );
		$instance['tweet_text_color'] = strip_tags( $new_instance['tweet_text_color'] );
		$instance['width'] = (int) strip_tags( $new_instance['width'] );
		
		$numberOfImages = (int) strip_tags( $new_instance['number_of_images'] );
		if($numberOfImages < 1 || $numberOfImages > MAX_IMAGES) {
			$numberOfImages = MAX_IMAGES;
		}
		$instance['number_of_images'] = $numberOfImages;
		
		$showTimestamps = strip_tags( $new_instance['show_timestamps'] );
		if($showTimestamps != 1) {
			$showTimestamps = 0;
		}
		$instance['show_timestamps'] = $showTimestamps;
		
		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance ) {
		
		/* Set up some default widget settings. */
		$instance = wp_parse_args( (array) $instance, array(
			'theme' => 'grid_vertical',
			'background_color' => 'FFFFFF',
			'border_color' => 'E2E2E2',
			'link_color' => '5C9FCC',
			'tweet_background_color' => 'F2F2F2',
			'tweet_text_color' => '222222',
			'width' => '250',
			'number_of_images' => '9',
			'show_timestamps' => '1'
		));

		/* Set up some default widget settings. */
		$instance = wp_parse_args( (array) $instance, null); 
?>

		<script type="text/javascript">
			function toggle_timestamp(option) {
				var style = "none";
				if(option == 'standard') { style = "inline"; }
				document.getElementById("<?php echo $this->get_field_id( 'show_timestamps' ); ?>").style.display = style;
			}
		</script>
		<table>
			<tr>
				<td>
					<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title: </label>
				</td>
				<td>
					<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:130px;" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="<?php echo $this->get_field_id( 'twitter_username' ); ?>">Twitter Username: </label>
				</td>
				<td>
					<input id="<?php echo $this->get_field_id( 'twitter_username' ); ?>" name="<?php echo $this->get_field_name( 'twitter_username' ); ?>" value="<?php echo $instance['twitter_username']; ?>" style="width:130px;" />	
				</td>
			</tr>
			<tr>
				<td>
					<label for="<?php echo $this->get_field_id( 'theme' ); ?>">Theme: </label>
				</td>
				<td>
					<select id="<?php echo $this->get_field_id( 'theme' ); ?>" name="<?php echo $this->get_field_name( 'theme' ); ?>" onchange="toggle_timestamp(this.value);" style="width:140px;">
						<option value="standard" <?php if($instance['theme'] == 'standard') { echo 'selected="selected"'; } ?>>Standard Vertical</option>
						<option value="grid_vertical" <?php if($instance['theme'] == 'grid_vertical') { echo 'selected="selected"'; } ?>>Thumbnail Grid</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<label for="<?php echo $this->get_field_id( 'background_color' ); ?>">Background Color: </label>
				</td>
				<td>
					<input type="text" id="<?php echo $this->get_field_id( 'background_color' ); ?>" name="<?php echo $this->get_field_name( 'background_color' ); ?>" value="<?php echo $instance['background_color']; ?>"  maxlength="6" style="width:70px;" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="<?php echo $this->get_field_id( 'border_color' ); ?>">Border Color: </label>
				</td>
				<td>
					<input type="text" id="<?php echo $this->get_field_id( 'border_color' ); ?>" name="<?php echo $this->get_field_name( 'border_color' ); ?>" value="<?php echo $instance['border_color']; ?>" maxlength="6" style="width:70px;" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="<?php echo $this->get_field_id( 'link_color' ); ?>">Link Color: </label>
				</td>
				<td>
					<input type="text" id="<?php echo $this->get_field_id( 'link_color' ); ?>" name="<?php echo $this->get_field_name( 'link_color' ); ?>" value="<?php echo $instance['link_color']; ?>" maxlength="6" style="width:70px;" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="<?php echo $this->get_field_id( 'tweet_background_color' ); ?>">Tweet Background Color: </label>
				</td>
				<td>
					<input type="text" id="<?php echo $this->get_field_id( 'tweet_background_color' ); ?>" name="<?php echo $this->get_field_name( 'tweet_background_color' ); ?>" value="<?php echo $instance['tweet_background_color']; ?>" maxlength="6" style="width:70px;" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="<?php echo $this->get_field_id( 'tweet_text_color' ); ?>">Tweet Text Color: </label>
				</td>
				<td>
					<input type="text" id="<?php echo $this->get_field_id( 'tweet_text_color' ); ?>" name="<?php echo $this->get_field_name( 'tweet_text_color' ); ?>" value="<?php echo $instance['tweet_text_color']; ?>" maxlength="6" style="width:70px;" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="<?php echo $this->get_field_id( 'width' ); ?>">Width: </label>
				</td>
				<td>
					<input type="text" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo $instance['width']; ?>"  style="width:70px;" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="<?php echo $this->get_field_id( 'number_of_images' ); ?>">Number of Images: </label>
				</td>
				<td>
					<input type="text" id="<?php echo $this->get_field_id( 'number_of_images' ); ?>" name="<?php echo $this->get_field_name( 'number_of_images' ); ?>" value="<?php echo $instance['number_of_images']; ?>" maxlength="2" style="width:40px" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="<?php echo $this->get_field_id( 'show_timestamps' ); ?>" id="timestamp_label">Show Timestamps: </label>
				</td>
				<td>
					<input type="checkbox" id="<?php echo $this->get_field_id( 'show_timestamps' ); ?>" name="<?php echo $this->get_field_name( 'show_timestamps' ); ?>" value="1" 
					<?php if($instance['show_timestamps'] == '1') { echo 'checked="checked"'; } ?>
					style="display:<?php if($instance['theme'] == 'standard') { echo "inline"; } else { echo "none"; } ?>;" />
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<a href="http://html-color-codes.info" target="_blank">html color codes</a>
				</td>
			</tr>
		</table>
		
	<?php
	}
}

?>