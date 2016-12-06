{if $type|eq('image')}
    {def $media = ngremotemedia($remote_value, '300x200', $availableFormats, true)}
    {def $thumb_url = $media.url}
{else}
    {def $thumb_url = videoThumbnail($remote_value)}
{/if}

<div class="ngremotemedia-image">
    {if $remote_value.resourceId}
        <div class="image-wrap">
            <img src="{$thumb_url}" />
        </div>

        <div class="image-meta">
            <h3 class="title">{$remote_value.resourceId|wash}</h3>

            <div class="tagger">
                <div class="ngremotemedia-alttext">
                    <span class="help-block description">{'Alternate text'|i18n('ngremotemedia')}</span>
                    <input type="text"
                           name="{$base}_alttext_{$fieldId}" value="{$remote_value.metaData.alt_text}" class="media-alttext data">
                </div>

                <div class="ngremotemedia-tags">
                    <div class="input-append add-tag">
                        <input type="text" class="tag no-autosave" placeholder="{'Add tag'|i18n( 'content/edit' )}" data-autosave="off">
                        <button class="btn tag" disabled type="button">{'Add tag'|i18n( 'content/edit' )}</button>
                    </div>
                    <div class="tags"></div>
                </div>

            </div>
            {if $remote_value.size|null()|not()}
            <p>
            {'Size'|i18n( 'content/edit' )}: {$remote_value.size|si( byte )}
            </p>
            {/if}
        </div>
    {/if}
</div>