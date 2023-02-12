<div class="col-sm commerce-category" role="main">
	<article class="content">
		$Content
	</article>

	<div class="row">
		<div class="col-auto">
			<% include CategoryDropdown %>
		</div>
		<div class="col-auto">
			<% include TagDropdown %>
		</div>
        <div class="col-auto ml-auto">
			$SortLimitForm
		</div>
	</div>

	<% if $PaginatedAllProducts(24).exists %>
		<div class="row">
			<% loop $PaginatedAllProducts(24) %>
				<div class="col-sm-6 col-lg-3 mb-4 catalogue-list-child">
					<% include ProductSummary %>
				</div>

				<% if $MultipleOf(4) %></div><div class="row"><% end_if %>
			<% end_loop %>
		</div>

		<% with $PaginatedAllProducts(24) %>
			<div class="text-center">
				<% include Pagination %>
			</div>
		<% end_with %>
	<% else %>
		<p class="text-center">
			<%t CatalogueFrontend.NoProducts "No products found" %>
		</p>
	<% end_if %>
</div>