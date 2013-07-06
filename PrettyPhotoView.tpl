<!-- Module PrettyPhotoView -->
{capture name=path}{l s='Gallery'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}
<form id="categoryFilter" action="gallery" method="get">
	<h1>Gallery</h1>
	<p>Click on an image to enlarge it!</p>
        <select name="categorySelector" id="categorySelector">
		{foreach from=$categories item=category}
			<option 
			{if $category == $smarty.get.categorySelector}
				selected="selected"
			{/if}	
			 id="{$category}">{$category}</option>
		{/foreach}
	</select>       
	<div id="prettyPhotoViewHolder" align="left" style="padding:0 0 10px 0;overflow: hidden; clear:both">
		<div id="wrap">
			<ul id="gallery">
				{foreach from=$xml item=my_item}
					<li>
						{if $my_item->img}
							<a href="{$path}{$my_item->img}" rel="prettyPhoto[pp_gal]" title="{$my_item->text}" style="color:transparent;">
								<input type="hidden" value="&lt;a href=&quot;{$my_item->url}&quot;&gt;{$my_item->text}&lt;/a&gt;" />
								<img src="{$path}{$my_item->thumb}" alt="{$my_item->text}" title="{$my_item->text}" style="margin: 5px;"/>
							</a>
						{/if}
					</li>
				{/foreach}
			</ul>
		</div>
                <div id="pahination" class="pagination">
                    {if $pagesTotal>1}
                    <ul class="pagination">
                        {if $previousPage eq 0}
                            <li id="pagination_previous" class="disabled"><span>«&nbsp;Previous</span></li>
                        {else}
                            <li id="pagination_previous"><a href="{$link->getPageLink('prettyPhotoGallery.php')}?p={$previousPage}">«&nbsp;Previous</a></li>
                        {/if}
                        {for $count=1 to $pagesTotal} 
                            {if $count eq $currentPage}     
                                <li class="current"><span>{$count}</span></li>
                            {else}
                                <li><a href="{$link->getPageLink('prettyPhotoGallery.php')}?p={$count}">{$count}</a></li>
                            {/if}
                        {/for}
                        {if $nextPage eq 0}
                            <li id="pagination_next" class="disabled"><span>Next&nbsp;»</li>
                        {else}
                            <li id="pagination_next"><a href="{$link->getPageLink('prettyPhotoGallery.php')}?p={$nextPage}">Next&nbsp;»</a></li>
                        {/if}                   
                    </ul>
		    {/if}
                </div>	
        </div>		
</form>
<script type="text/javascript">
{literal}
	$(document).ready(function(){
	    $("a[rel^='prettyPhoto']").prettyPhoto();
	 });
	 
	$('#categorySelector').change(function() {
	  $('#categoryFilter').submit();
	});
{/literal}
</script>
<!-- /Module prettyPhotoView -->
