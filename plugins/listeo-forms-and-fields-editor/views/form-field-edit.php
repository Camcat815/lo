
<div class="edit-form-field" style="display: none;">

	<div id="listeo-field-<?php echo $field_key; ?>">

		<p class="name-container">
			<label for="label">Name</label>
			<input type="text" class="input-text" name="name[<?php echo esc_attr($index); ?>]" value="<?php echo esc_attr($field['name']); ?>" />
		</p>
		<?php
		$blocked_fileds = array('_price', '_price_per', '_offer_type', '_property_type', '_rental_period', '_area', '_friendly_address', '_address', '_geolocation_lat', '_geolocation_long');

		?>

		<p class="field-id" <?php if (isset($field['id']) && in_array($field['id'], $blocked_fileds)) {
								echo 'style="display:none"';
							} ?>>
			<label for="label">ID <span class="dashicons dashicons-editor-help" title="Do not edit if you don't know what you are doing :)"></span></label>
			<input type="text" class="input-text" name="id[<?php echo esc_attr($index); ?>]" value="<?php echo esc_attr(isset($field['id']) ? $field['id'] : ''); ?>" />
		</p>
		<p class="field-type">
			<label for="type">Type</label>
			<select name="type[<?php echo esc_attr($index); ?>]">
				<?php
				foreach ($field_types as $key => $type) {
					echo '<option value="' . esc_attr($key) . '" ' . selected($field['type'], $key, false) . '>' . esc_html($type) . '</option>';
				}
				?>
			</select>
		</p>
		<p class="field-icon">
			<label for="icon"><?php esc_html_e('Category Icon', 'listeo_core'); ?></label>
			<select class="listeo-icon-select" name="icon[<?php echo esc_attr($index); ?>]" id="icon">
				<option value=" ">Empty Icon</option>
				<?php

				// $faicons = listeo_fa_icons_list();

				//  	foreach ($faicons as $key => $value) {

				//  		echo '<option value="fa fa-'.$key.'" ';
				//  		echo '>'.$value.'</option>';
				//  	}

				$faicons = listeo_fa_icons_list();
				$icon   = (isset($field['icon'])) ? $field['icon'] : '';
				foreach ($faicons as $key => $value) {
					if ($key) {
						echo '<option value="' . $key . '" ';
						if ($icon == $key) {
							echo ' selected="selected"';
						}
						echo '>' . $value . '</option>';
					}
				}

				// if(!get_option('listeo_iconsmind')=='hide'){
				// 	$imicons = vc_iconpicker_type_iconsmind(array());

				//    	foreach ($imicons as $key => $icon_array ) {
				//    		$key = key($icon_array);
				//    		$value = $icon_array[$key];
				//    		echo '<option value="'.$key.'" ';
				//    			if(isset($icon) && $icon == $key) { echo ' selected="selected"';}
				//    		echo '>'.$value.'</option>';
				//    	}
				// }

				?>

			</select>

		</p>
		<?php if (in_array($tab, array('events_tab', 'service_tab', 'rental_tab', 'classifieds_tab'))) : ?>
			<p class="invert-container">
				<label for="invert">Show value before label</label>
				<input name="invert[<?php echo esc_attr($index); ?>]" type="checkbox" <?php if (isset($field['invert'])) checked($field['invert'], 1, true); ?> value="1">
			</p>
		<?php endif; ?>
		<p class="field-desc">
			<label for="desc">Decription <span class="dashicons dashicons-editor-help" title="Description for the field, displayed in back-end"></span></label>
			<textarea rows="4" cols="10" class="input-text" name="desc[<?php echo esc_attr($index); ?>]"><?php if (isset($field['desc'])) {
																												echo esc_attr($field['desc']);
																											} ?></textarea>
		</p>
		<div class="field-options">
			<label for="options">Options</label>
			<?php
			$source = '';
			if (!isset($field['options_source'])) {
				if (isset($field['options_cb']) && !empty($field['options_cb'])) {
					$source = 'predefined';
				};
			} else {
				$source = '';
			};

			if (isset($field['options_source']) && empty($field['options_source'])) {
				if (isset($field['options_cb']) && !empty($field['options_cb'])) {
					$source = 'predefined';
				};
			}
			if (isset($field['options_source']) && !empty($field['options_source'])) {
				$source = $field['options_source'];
			} ?>
			<!-- 	<select name="options_source[<?php echo esc_attr($index); ?>]" class="field-options-data-source-choose">
				<option  value="">--Select Option--</option>
				<option <?php selected($source, 'predefined'); ?> value="predefined">Predefined List</option>
				<option <?php selected($source, 'custom'); ?> value="custom">Custom Options list</option>
			</select> -->
			<div class="options">

				<table class="field-options-custom">
					<thead>
						<tr>
							<td>Value</td>
							<td>Name</td>
							<td></td>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td colspan="3">
								<a class="add-new-option-table" href="#">Add</a>
							</td>
						</tr>
					</tfoot>
					<tbody data-field="<?php echo esc_attr("
					<tr>
						<td>
							<input type='text' class='input-text options' name='options[{$index}][-1][name]' />
						</td>
						<td>
							<input type='text' class='input-text options' name='options[{$index}][-1][value]' />
						</td>
						<td class='remove_row'>x</td>
					</tr>"); ?>">
						<?php if (isset($field['options']) && is_array($field['options'])) {
							$i = 0;
							foreach ($field['options'] as $key => $value) {
						?>
								<tr>
									<td>
										<input type="text" value="<?php echo esc_attr($key); ?>" class="input-text options" name="options[<?php echo esc_attr($index); ?>][<?php echo esc_attr($i); ?>][name]" />
									</td>
									<td>
										<input type="text" value="<?php echo esc_attr($value); ?>" class="input-text options" name="options[<?php echo esc_attr($index); ?>][<?php echo esc_attr($i); ?>][value]" />
									</td>
									<td class="remove_row">x</td>
								</tr>
						<?php
								$i++;
							}
						}; ?>
					</tbody>
				</table>
			</div>
		</div>
		<p>
			<label for="">Default value</label>
			<input type="text" class="input-text" name="default[<?php echo esc_attr($index); ?>]" value="<?php if (isset($field['default'])) {
																												echo esc_attr($field['default']);
																											} ?>" />
		</p>
		<p class="listeo-editor-placeholder-field">
			<label for="">Placeholder value</label>
			<input type="text" class="input-text" name="placeholder[<?php echo esc_attr($index); ?>]" value="<?php if (isset($field['placeholder'])) {
																													echo esc_attr($field['placeholder']);
																												} ?>" />
		</p>
		<p class="listeo-editor-css-field">
			<label for="">CSS class </label>
			<input type="text" class="input-text" name="css[<?php echo esc_attr($index); ?>]" value="<?php if (isset($field['css'])) {
																											echo esc_attr($field['css']);
																										} ?>" />
		</p>
		<p class="listeo-editor-width-field">
			<label for="">Width</label>

			<select name="width[<?php echo esc_attr($index); ?>]" id="">
				<option <?php if (isset($field['width'])) selected($field['width'], 'col-md-6'); ?> value="col-md-6">Half</option>
				<option <?php if (isset($field['width'])) selected($field['width'], 'col-md-12'); ?> value="col-md-12">Full-width</option>
			</select>

		</p>

	</div>
</div>