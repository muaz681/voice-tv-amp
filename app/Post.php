<?php

namespace App;

use Corcel\Model\Post as Corcel;
use Illuminate\Http\Request;

class Post extends Corcel
{
	protected $hidden = ['post_content', 'thumbnail', 'taxonomies'];
	protected $appends = [
		'id',
		'title',
		'slug',
		'content',
		'type',
		'mime_type',
		'url',
		'author_id',
		'parent_id',
		'created_at',
		'updated_at',
		'excerpt',
		'status',
		'image',
		'terms',
		'main_category',
		'categories',
		'keywords',
		'keywords_str',
		'media_sizes',
		'seo'
	];

	protected $seoKeys = [
		'_yoast_wpseo_focuskw',
		'_yoast_wpseo_title',
		'_yoast_wpseo_desc',
		'_yoast_wpseo_noindex',
		'_yoast_wpseo_opengraph-title',
		'_yoast_wpseo_opengraph-description',
		'_yoast_wpseo_opengraph-image',
		'_yoast_wpseo_twitter-title',
		'_yoast_wpseo_twitter-description',
		'_yoast_wpseo_twitter-image',
	];

	public function getSeoAttribute() {
		$meta = $this->meta;
		$arr = array_fill_keys($this->seoKeys, '');

		foreach ($meta as $key => $m) {
			if(in_array($m->meta_key, $this->seoKeys)) {
				$arr[$m->meta_key] = $m->meta_value;
			}
		}
		$arr += [
			'title' => $this->title,
			'description' => substr($this->excerpt, 0, 160),
			'post_date_gmt' => $this->post_date_gmt,
			'post_modified_gmt' => $this->post_modified_gmt,
			'image_width' => empty($this->media_sizes->width) ? '' : $this->media_sizes->width,
			'image_height' => empty($this->media_sizes->height) ? '' : $this->media_sizes->height,
		];
		return $arr;
	}

	public function getCategoriesAttribute()
	{
		$mainCategory = 'Uncategorized';
		$arr = [];
		foreach ($this->taxonomies as $tx) {
			if ($tx->taxonomy === 'category') {
				$arr[] = $tx->term;
			}
		}
		return $arr;
		if (!empty($this->taxonomies)) {
			$taxonomies = array_values($this->terms);
			return $taxonomies[0];
		}

		return $mainCategory;
	}

	public function getIdAttribute(){
		return (int) substr($this->url, strpos($this->url, '?p=') + 3);
	}

	public function getExcerptAttribute() {
		if (empty($this->post_excerpt)) {
			$excerpt = $this->trim_excerpt($this->content, 300, '…');
		} else {
			$excerpt = $this->post_excerpt;
		}
		//$this->post_excerpt = $this->excerpt = $excerpt;
		return $excerpt;
	}

	public function getPostExcerptAttribute() {
		return $this->getExcerptAttribute();
	}

	public function getMediaSizesAttribute() {
		if (!empty($this->thumbnail->attachment->meta[1]->value)) {
			return $this->thumbnail->attachment->meta[1]->value;
		}
	}

	private function trim_excerpt( $text, $length = 55, $more = ' [&hellip;]'  )
	{
		//$text = $this->stripShortcodes( $text );
		$text = str_replace(']]>', ']]&gt;', $text);

		$excerpt = $this->trim_words( $text, $length, $more );
		return $excerpt;
	}

	private function trim_words( $text, $num_words = 55, $more = null ) {

		if ( null === $more ) {
			$more = __( '&hellip;' );
		}

		$original_text = $text;
		$text          = $this->strip_all_tags( $text );
		$num_words     = (int) $num_words;

		/*
		 * translators: If your word count is based on single characters (e.g. East Asian characters),
		 * enter 'characters_excluding_spaces' or 'characters_including_spaces'. Otherwise, enter 'words'.
		 * Do not translate into your own language.
		 */
		if ( preg_match( '/^utf\-?8$/i', 'utf8' ) ) {
			$text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $text ), ' ' );
			preg_match_all( '/./u', $text, $words_array );
			$words_array = array_slice( $words_array[0], 0, $num_words + 1 );
			$sep         = '';
		} else {
			$words_array = preg_split( "/[\n\r\t ]+/", $text, $num_words + 1, PREG_SPLIT_NO_EMPTY );
			$sep         = ' ';
		}

		if ( count( $words_array ) > $num_words ) {
			array_pop( $words_array );
			$text = implode( $sep, $words_array );
			$text = $text . $more;
		} else {
			$text = implode( $sep, $words_array );
		}
		return $text;
	}

	private function strip_all_tags( $string, $remove_breaks = false ) {
		$string = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $string );
		$string = strip_tags( $string );

		if ( $remove_breaks ) {
			$string = preg_replace( '/[\r\n\t ]+/', ' ', $string );
		}

		return trim( $string );
	}

	public function customMethod() {
		//
	}
}