<?php
/**
 * Code Highlighter Element.
 *
 * @package JWR_Customizations
 */

namespace JWR_Customizations\includes\common\custom_bricks_elements\code_highlighter;

use Bricks\Element;

defined( 'ABSPATH' ) || exit;

/**
 * Code Highlighter Element.
 *
 * @return void
 */
class Code_Highlighter_Block extends Element {

	/**
	 * Element category.
	 *
	 * @var string
	 */
	public $category = 'custom';
	/**
	 * Element name.
	 *
	 * @var string
	 */
	public $name = 'jwr-code-highlighter';
	/**
	 * Element icon.
	 *
	 * @var string
	 */
	public $icon = 'fa-solid fa-code';
	/**
	 * Element CSS class.
	 *
	 * @var string
	 */
	public $css_selector = '.wpmb-code-highlighter';

	/**
	 * Get label.
	 *
	 * @return string
	 */
	public function get_label() {
		return 'Code Highlighter';
	}

	/**
	 * Set controls
	 *
	 * @return void
	 */
	public function set_controls() {
		// Add title field.
		$this->controls['title'] = array(
			'type'           => 'text',
			'hasDynamicData' => 'text',
			'label'          => 'Title',
			'default'        => 'Set the title, Josh',
			'placeholder'    => 'Add the title here.',

		);

		// Add code content field.
		$this->controls['code_content'] = array(
			'type'           => 'textarea',
			'hasDynamicData' => 'text',
			'label'          => 'Code',
			'default'        => '',
			'rows'           => 8,
		);
	}

	/**
	 * Enqueue scripts.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_script(
			'highlight-js',
			'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js',
			array(),
			'11.7.0',
			true
		);

		// Enqueue specific languages including json, sql, and scss.
		// [] Test how this affects performance. It could be unnecessary.
		$languages = array( 'javascript', 'php', 'html', 'css', 'bash', 'python', 'json', 'sql', 'scss' );
		foreach ( $languages as $language ) {
			wp_enqueue_script(
				'highlight-js-' . $language,
				'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/languages/' . $language . '.min.js',
				array( 'highlight-js' ),
				'11.7.0',
				true
			);
		}

		// Add highlight . js default stylesheet .
		wp_enqueue_style(
			'highlight-js-theme',
			'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/tomorrow-night-blue.min.css',
			array(),
			'11.7.0'
		);

		// Enqueue base CSS for the code block.
		wp_enqueue_style(
			'code-highlighter-css',
			JWR_URL . '/includes/common/custom-bricks-elements/code-highlighter/code-highlighter.css',
			array(),
			filemtime( JWR_PATH . '/includes/common/custom-bricks-elements/code-highlighter/code-highlighter.css' ) ?? '1',
		);

			// Initialize highlight . js .
			wp_add_inline_script( 'highlight-js', 'hljs.highlightAll();' );
	}

	/**
	 * Render element.
	 *
	 * @return void
	 */
	public function render() {
		// Get title, code content, and language from settings.
		$title        = $this->settings['title'] ?? '';
		$code_content = $this->settings['code_content'] ?? '';

		echo wp_kses_post( "<div {$this->render_attributes( '_root' )}>" );

		// Output the title.
		if ( ! empty( $title ) ) {
			echo '<h3>' . esc_html( $title ) . '</h3>';
		}

		// Output the code block with the selected language class.
		// echo '<pre><code>' . esc_html( $code_content ) . '</code></pre>';
		echo '<pre><code>' . $code_content . '</code></pre>';

		echo '</div>';
	}

	/**
	 * Keywords
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'code', 'highlighter', 'highlight', 'syntax', 'highlighting' );
	}
}
