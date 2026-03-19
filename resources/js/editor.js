/**
 * Block editor script — InspectorControls for all custom blocks.
 *
 * @wordpress/blocks, @wordpress/block-editor, @wordpress/components,
 * @wordpress/server-side-render and @wordpress/element are all mapped
 * to window.wp.* externals by @roots/vite-plugin's wordpressPlugin().
 */

import domReady from '@wordpress/dom-ready'
import { registerBlockType } from '@wordpress/blocks'
import {
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
  useBlockProps,
} from '@wordpress/block-editor'
import {
  PanelBody,
  TextControl,
  TextareaControl,
  RangeControl,
  SelectControl,
  ToggleControl,
  Button,
} from '@wordpress/components'
import ServerSideRender from '@wordpress/server-side-render'
import { createElement as el, Fragment } from '@wordpress/element'
import { __ } from '@wordpress/i18n'

// ── Shared helpers ────────────────────────────────────────────────────────────

const bgOptions = [
  { label: __('Chiaro (surface)', 'sage'), value: 'surface' },
  { label: __('Crema', 'sage'),            value: 'cream'   },
  { label: __('Scuro (ink)', 'sage'),      value: 'ink'     },
]

function MediaPanel({ imageId, imageUrl, onSelect, onRemove }) {
  return el(
    MediaUploadCheck, null,
    el(MediaUpload, {
      onSelect,
      allowedTypes: ['image'],
      value: imageId || 0,
      render: ({ open }) => el(
        Fragment, null,
        el(Button, {
          onClick: open,
          variant: imageId ? 'secondary' : 'primary',
          style: { marginBottom: '8px', display: 'block', width: '100%' },
        }, imageId
          ? __('Cambia immagine', 'sage')
          : __('Scegli immagine', 'sage')
        ),
        imageUrl && el('img', {
          src: imageUrl,
          alt: '',
          style: { maxWidth: '100%', height: 'auto', marginBottom: '8px', borderRadius: '4px' },
        }),
        imageId && el(Button, {
          isDestructive: true,
          variant: 'link',
          onClick: onRemove,
          style: { display: 'block' },
        }, __('Rimuovi immagine', 'sage')),
      ),
    }),
  )
}

// ── theme/hero ────────────────────────────────────────────────────────────────

domReady(() => {
  registerBlockType('theme/hero', {
    edit({ attributes, setAttributes }) {
      const {
        imageId, imageUrl, label, heading, subtext,
        ctaLabel, ctaUrl, cta2Label, cta2Url,
        overlayOpacity, minHeight, contentAlign,
      } = attributes

      return el(
        Fragment, null,

        el(InspectorControls, null,

          el(PanelBody, { title: __('Immagine di sfondo', 'sage'), initialOpen: true },
            el(MediaPanel, {
              imageId,
              imageUrl,
              onSelect: (media) => setAttributes({ imageId: media.id, imageUrl: media.url, imageAlt: media.alt ?? '' }),
              onRemove: () => setAttributes({ imageId: 0, imageUrl: '', imageAlt: '' }),
            }),
            el(RangeControl, {
              label: __('Opacità overlay', 'sage'),
              value: overlayOpacity ?? 40,
              onChange: (val) => setAttributes({ overlayOpacity: val }),
              min: 0, max: 100,
            }),
          ),

          el(PanelBody, { title: __('Contenuto', 'sage'), initialOpen: true },
            el(TextControl, {
              label: __('Label piccola', 'sage'),
              value: label ?? '',
              onChange: (val) => setAttributes({ label: val }),
            }),
            el(TextControl, {
              label: __('Titolo principale', 'sage'),
              value: heading ?? '',
              onChange: (val) => setAttributes({ heading: val }),
            }),
            el(TextareaControl, {
              label: __('Sottotitolo', 'sage'),
              value: subtext ?? '',
              onChange: (val) => setAttributes({ subtext: val }),
            }),
          ),

          el(PanelBody, { title: __('Pulsanti CTA', 'sage'), initialOpen: false },
            el(TextControl, {
              label: __('Testo CTA principale', 'sage'),
              value: ctaLabel ?? '',
              onChange: (val) => setAttributes({ ctaLabel: val }),
            }),
            el(TextControl, {
              label: __('URL CTA principale', 'sage'),
              value: ctaUrl ?? '',
              type: 'url',
              onChange: (val) => setAttributes({ ctaUrl: val }),
            }),
            el(TextControl, {
              label: __('Testo CTA secondario', 'sage'),
              value: cta2Label ?? '',
              onChange: (val) => setAttributes({ cta2Label: val }),
            }),
            el(TextControl, {
              label: __('URL CTA secondario', 'sage'),
              value: cta2Url ?? '',
              type: 'url',
              onChange: (val) => setAttributes({ cta2Url: val }),
            }),
          ),

          el(PanelBody, { title: __('Layout', 'sage'), initialOpen: false },
            el(SelectControl, {
              label: __('Allineamento contenuto', 'sage'),
              value: contentAlign ?? 'center',
              options: [
                { label: __('Sinistra', 'sage'),  value: 'left'   },
                { label: __('Centro', 'sage'),    value: 'center' },
                { label: __('Destra', 'sage'),    value: 'right'  },
              ],
              onChange: (val) => setAttributes({ contentAlign: val }),
            }),
            el(TextControl, {
              label: __('Altezza minima', 'sage'),
              value: minHeight ?? '100svh',
              help: __('es. 100svh, 600px, 80vh', 'sage'),
              onChange: (val) => setAttributes({ minHeight: val }),
            }),
          ),
        ),

        el('div', useBlockProps(),
          el(ServerSideRender, { block: 'theme/hero', attributes }),
        ),
      )
    },
    save: () => null,
  })

  // ── theme/testimonial ───────────────────────────────────────────────────────

  registerBlockType('theme/testimonial', {
    edit({ attributes, setAttributes }) {
      const {
        quote, authorName, authorRole,
        authorImageId, authorImageUrl,
        rating, bg, style: cardStyle,
      } = attributes

      return el(
        Fragment, null,

        el(InspectorControls, null,

          el(PanelBody, { title: __('Citazione', 'sage'), initialOpen: true },
            el(TextareaControl, {
              label: __('Citazione', 'sage'),
              value: quote ?? '',
              onChange: (val) => setAttributes({ quote: val }),
            }),
            el(RangeControl, {
              label: __('Valutazione (stelle)', 'sage'),
              value: rating ?? 5,
              onChange: (val) => setAttributes({ rating: val }),
              min: 0, max: 5,
            }),
          ),

          el(PanelBody, { title: __('Autore', 'sage'), initialOpen: true },
            el(TextControl, {
              label: __('Nome autore', 'sage'),
              value: authorName ?? '',
              onChange: (val) => setAttributes({ authorName: val }),
            }),
            el(TextControl, {
              label: __('Ruolo / azienda', 'sage'),
              value: authorRole ?? '',
              onChange: (val) => setAttributes({ authorRole: val }),
            }),
            el(MediaPanel, {
              imageId: authorImageId,
              imageUrl: authorImageUrl,
              onSelect: (media) => setAttributes({ authorImageId: media.id, authorImageUrl: media.url }),
              onRemove: () => setAttributes({ authorImageId: 0, authorImageUrl: '' }),
            }),
          ),

          el(PanelBody, { title: __('Stile', 'sage'), initialOpen: false },
            el(SelectControl, {
              label: __('Stile card', 'sage'),
              value: cardStyle ?? 'card',
              options: [
                { label: __('Card', 'sage'),    value: 'card'    },
                { label: __('Minimal', 'sage'), value: 'minimal' },
                { label: __('Grande', 'sage'),  value: 'large'   },
              ],
              onChange: (val) => setAttributes({ style: val }),
            }),
            el(SelectControl, {
              label: __('Sfondo', 'sage'),
              value: bg ?? 'surface',
              options: bgOptions,
              onChange: (val) => setAttributes({ bg: val }),
            }),
          ),
        ),

        el('div', useBlockProps(),
          el(ServerSideRender, { block: 'theme/testimonial', attributes }),
        ),
      )
    },
    save: () => null,
  })

  // ── theme/stat ──────────────────────────────────────────────────────────────

  registerBlockType('theme/stat', {
    edit({ attributes, setAttributes }) {
      const {
        value, label, description,
        prefix, suffix, align, bg,
      } = attributes

      return el(
        Fragment, null,

        el(InspectorControls, null,

          el(PanelBody, { title: __('Dati', 'sage'), initialOpen: true },
            el(TextControl, {
              label: __('Prefisso (es. +, €)', 'sage'),
              value: prefix ?? '',
              onChange: (val) => setAttributes({ prefix: val }),
            }),
            el(TextControl, {
              label: __('Valore (es. 100+)', 'sage'),
              value: value ?? '',
              onChange: (val) => setAttributes({ value: val }),
            }),
            el(TextControl, {
              label: __('Suffisso (es. %, k)', 'sage'),
              value: suffix ?? '',
              onChange: (val) => setAttributes({ suffix: val }),
            }),
            el(TextControl, {
              label: __('Etichetta', 'sage'),
              value: label ?? '',
              onChange: (val) => setAttributes({ label: val }),
            }),
            el(TextControl, {
              label: __('Descrizione opzionale', 'sage'),
              value: description ?? '',
              onChange: (val) => setAttributes({ description: val }),
            }),
          ),

          el(PanelBody, { title: __('Stile', 'sage'), initialOpen: false },
            el(SelectControl, {
              label: __('Allineamento', 'sage'),
              value: align ?? 'left',
              options: [
                { label: __('Sinistra (con linea)', 'sage'), value: 'left'   },
                { label: __('Centro', 'sage'),               value: 'center' },
              ],
              onChange: (val) => setAttributes({ align: val }),
            }),
            el(SelectControl, {
              label: __('Sfondo sezione', 'sage'),
              value: bg ?? 'surface',
              options: bgOptions,
              onChange: (val) => setAttributes({ bg: val }),
            }),
          ),
        ),

        el('div', useBlockProps(),
          el(ServerSideRender, { block: 'theme/stat', attributes }),
        ),
      )
    },
    save: () => null,
  })

  // ── theme/icon-box ──────────────────────────────────────────────────────────

  registerBlockType('theme/icon-box', {
    edit({ attributes, setAttributes }) {
      const {
        iconImageId, iconImageUrl, iconSize,
        title, text, linkLabel, linkUrl,
        bg, layout, bordered,
      } = attributes

      return el(
        Fragment, null,

        el(InspectorControls, null,

          el(PanelBody, { title: __('Icona', 'sage'), initialOpen: true },
            el(MediaPanel, {
              imageId: iconImageId,
              imageUrl: iconImageUrl,
              onSelect: (media) => setAttributes({ iconImageId: media.id, iconImageUrl: media.url }),
              onRemove: () => setAttributes({ iconImageId: 0, iconImageUrl: '' }),
            }),
            el(RangeControl, {
              label: __('Dimensione icona (px)', 'sage'),
              value: iconSize ?? 48,
              onChange: (val) => setAttributes({ iconSize: val }),
              min: 16, max: 120,
            }),
          ),

          el(PanelBody, { title: __('Contenuto', 'sage'), initialOpen: true },
            el(TextControl, {
              label: __('Titolo', 'sage'),
              value: title ?? '',
              onChange: (val) => setAttributes({ title: val }),
            }),
            el(TextareaControl, {
              label: __('Testo', 'sage'),
              value: text ?? '',
              onChange: (val) => setAttributes({ text: val }),
            }),
            el(TextControl, {
              label: __('Testo link', 'sage'),
              value: linkLabel ?? '',
              onChange: (val) => setAttributes({ linkLabel: val }),
            }),
            el(TextControl, {
              label: __('URL link', 'sage'),
              value: linkUrl ?? '',
              type: 'url',
              onChange: (val) => setAttributes({ linkUrl: val }),
            }),
          ),

          el(PanelBody, { title: __('Stile', 'sage'), initialOpen: false },
            el(SelectControl, {
              label: __('Layout', 'sage'),
              value: layout ?? 'vertical',
              options: [
                { label: __('Verticale', 'sage'),    value: 'vertical'   },
                { label: __('Orizzontale', 'sage'),  value: 'horizontal' },
              ],
              onChange: (val) => setAttributes({ layout: val }),
            }),
            el(SelectControl, {
              label: __('Sfondo', 'sage'),
              value: bg ?? 'surface',
              options: bgOptions,
              onChange: (val) => setAttributes({ bg: val }),
            }),
            el(ToggleControl, {
              label: __('Bordo card', 'sage'),
              checked: bordered ?? false,
              onChange: (val) => setAttributes({ bordered: val }),
            }),
          ),
        ),

        el('div', useBlockProps(),
          el(ServerSideRender, { block: 'theme/icon-box', attributes }),
        ),
      )
    },
    save: () => null,
  })
})
