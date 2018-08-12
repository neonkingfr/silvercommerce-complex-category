<% if $IncludeFormTag %>
<form $AttributesHTML>
<% end_if %>
    <% if $Legend %><legend>$Legend</legend><% end_if %>
    <% loop $Fields %>
        $FieldHolder
    <% end_loop %>
    <div class="clear"><!-- --></div>
<% if $IncludeFormTag %>
</form>
<% end_if %>
