<?php
/**
 * Title: Recensioni Aggregate – Rating + Badge
 * Slug: theme/review-aggregate
 * Categories: theme-sections
 * Keywords: recensioni, rating, stelle, review, trustpilot, google, badge, testimonianze
 * Description: Sezione rating aggregato con punteggio medio, numero recensioni, badge piattaforme e 3 recensioni in evidenza.
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"backgroundColor":"ink","layout":{"type":"constrained"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|9","bottom":"var:preset|spacing|9"}}}} -->
<div class="wp-block-group has-ink-background-color has-background">

	<!-- Punteggio aggregato -->
	<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center","verticalAlignment":"center"},"style":{"spacing":{"blockGap":"3rem","padding":{"bottom":"3rem"},"margin":{"bottom":"3rem"}},"border":{"bottom":{"color":"rgba(255,255,255,0.1)","width":"1px","style":"solid"}}}} -->
	<div class="wp-block-group review-aggregate-header">

		<!-- Rating numerico -->
		<!-- wp:group {"style":{"spacing":{"blockGap":"0.25rem"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group gap-1 text-center">
			<!-- wp:heading {"level":2,"textColor":"white","fontSize":"hero","fontFamily":"serif","style":{"spacing":{"margin":{"bottom":"0"}}}} -->
			<h2 class="wp-block-heading has-white-color has-text-color has-serif-font-family has-hero-font-size mb-0">4.9</h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"textColor":"accent","fontSize":"xs","className":"section-label"} -->
			<p class="has-accent-color has-text-color has-xs-font-size section-label">SU 5 STELLE</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"fontSize":"sm","style":{"color":{"text":"rgba(255,255,255,0.5)"}}} -->
			<p class="has-text-color has-sm-font-size text-white/50">Basato su 328 recensioni</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- Separatore verticale (visibile solo desktop) -->
		<!-- wp:group {"style":{"dimensions":{"minHeight":"80px"},"border":{"left":{"color":"rgba(255,255,255,0.1)","width":"1px","style":"solid"}}},"layout":{"type":"default"}} -->
		<div class="wp-block-group min-h-20 border-l border-white/10"></div>
		<!-- /wp:group -->

		<!-- Badge piattaforme -->
		<!-- wp:group {"style":{"spacing":{"blockGap":"1rem"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
		<div class="wp-block-group gap-4">

			<!-- Google -->
			<!-- wp:group {"style":{"border":{"width":"1px","style":"solid","color":"rgba(255,255,255,0.15)"},"spacing":{"padding":{"top":"0.75rem","bottom":"0.75rem","left":"1.25rem","right":"1.25rem"},"blockGap":"0.5rem"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
			<div class="wp-block-group review-badge">
				<!-- wp:paragraph {"textColor":"white","fontSize":"sm","style":{"spacing":{"margin":{"bottom":"0"}}}} -->
				<p class="has-white-color has-text-color has-sm-font-size mb-0">⭐ <strong>4.9</strong> Google</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->

			<!-- Trustpilot -->
			<!-- wp:group {"style":{"border":{"width":"1px","style":"solid","color":"rgba(255,255,255,0.15)"},"spacing":{"padding":{"top":"0.75rem","bottom":"0.75rem","left":"1.25rem","right":"1.25rem"},"blockGap":"0.5rem"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
			<div class="wp-block-group review-badge">
				<!-- wp:paragraph {"textColor":"white","fontSize":"sm","style":{"spacing":{"margin":{"bottom":"0"}}}} -->
				<p class="has-white-color has-text-color has-sm-font-size mb-0">⭐ <strong>4.8</strong> Trustpilot</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->

		</div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

	<!-- 3 recensioni -->
	<!-- wp:columns {"isStackedOnMobile":true,"style":{"spacing":{"blockGap":{"left":"1.5rem","top":"1.5rem"}}}} -->
	<div class="wp-block-columns">

		<!-- Recensione 1 -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"style":{"border":{"width":"1px","style":"solid","color":"rgba(255,255,255,0.1)"},"spacing":{"padding":{"all":"1.75rem"},"blockGap":"1rem"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group review-card">
				<!-- Stelle -->
				<!-- wp:paragraph {"textColor":"accent","fontSize":"sm","style":{"spacing":{"margin":{"bottom":"0"}}}} -->
				<p class="has-accent-color has-text-color has-sm-font-size mb-0">★★★★★</p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"fontSize":"base","style":{"color":{"text":"rgba(255,255,255,0.85)"}}} -->
				<p class="has-text-color has-base-font-size text-white/85">"Professionalità e competenza fuori dal comune. Il sito realizzato ha superato ogni nostra aspettativa, sia per il design che per le performance."</p>
				<!-- /wp:paragraph -->
				<!-- wp:group {"style":{"spacing":{"blockGap":"0.25rem"}},"layout":{"type":"default"}} -->
				<div class="wp-block-group gap-1">
					<!-- wp:paragraph {"textColor":"white","fontSize":"sm","style":{"spacing":{"margin":{"bottom":"0"}}}} -->
					<p class="has-white-color has-text-color has-sm-font-size mb-0"><strong>Marco Bianchi</strong></p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph {"fontSize":"xs","style":{"color":{"text":"rgba(255,255,255,0.4)"},"spacing":{"margin":{"top":"0"}}}} -->
					<p class="has-text-color has-xs-font-size text-white/40 mt-0">CEO, Azienda Srl</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- Recensione 2 -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"style":{"border":{"width":"1px","style":"solid","color":"rgba(255,255,255,0.1)"},"spacing":{"padding":{"all":"1.75rem"},"blockGap":"1rem"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group review-card">
				<!-- Stelle -->
				<!-- wp:paragraph {"textColor":"accent","fontSize":"sm","style":{"spacing":{"margin":{"bottom":"0"}}}} -->
				<p class="has-accent-color has-text-color has-sm-font-size mb-0">★★★★★</p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"fontSize":"base","style":{"color":{"text":"rgba(255,255,255,0.85)"}}} -->
				<p class="has-text-color has-base-font-size text-white/85">"Tempi rispettati, comunicazione impeccabile e risultato finale eccellente. Consigliatissimi a chiunque voglia un progetto digitale di qualità."</p>
				<!-- /wp:paragraph -->
				<!-- wp:group {"style":{"spacing":{"blockGap":"0.25rem"}},"layout":{"type":"default"}} -->
				<div class="wp-block-group gap-1">
					<!-- wp:paragraph {"textColor":"white","fontSize":"sm","style":{"spacing":{"margin":{"bottom":"0"}}}} -->
					<p class="has-white-color has-text-color has-sm-font-size mb-0"><strong>Laura Rossi</strong></p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph {"fontSize":"xs","style":{"color":{"text":"rgba(255,255,255,0.4)"},"spacing":{"margin":{"top":"0"}}}} -->
					<p class="has-text-color has-xs-font-size text-white/40 mt-0">Marketing Manager, Studio XY</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- Recensione 3 -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"style":{"border":{"width":"1px","style":"solid","color":"rgba(255,255,255,0.1)"},"spacing":{"padding":{"all":"1.75rem"},"blockGap":"1rem"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group review-card">
				<!-- Stelle -->
				<!-- wp:paragraph {"textColor":"accent","fontSize":"sm","style":{"spacing":{"margin":{"bottom":"0"}}}} -->
				<p class="has-accent-color has-text-color has-sm-font-size mb-0">★★★★★</p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"fontSize":"base","style":{"color":{"text":"rgba(255,255,255,0.85)"}}} -->
				<p class="has-text-color has-base-font-size text-white/85">"Lavoro impeccabile dalla fase di analisi al lancio. Hanno capito subito le nostre esigenze e tradotto tutto in un prodotto digitale che funziona davvero."</p>
				<!-- /wp:paragraph -->
				<!-- wp:group {"style":{"spacing":{"blockGap":"0.25rem"}},"layout":{"type":"default"}} -->
				<div class="wp-block-group gap-1">
					<!-- wp:paragraph {"textColor":"white","fontSize":"sm","style":{"spacing":{"margin":{"bottom":"0"}}}} -->
					<p class="has-white-color has-text-color has-sm-font-size mb-0"><strong>Andrea Ferrari</strong></p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph {"fontSize":"xs","style":{"color":{"text":"rgba(255,255,255,0.4)"},"spacing":{"margin":{"top":"0"}}}} -->
					<p class="has-text-color has-xs-font-size text-white/40 mt-0">Founder, Startup Innovativa</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
