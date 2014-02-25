// JavaScript Document
function rowSelection(selectedRow, fieldName, gridId)
{
	var rowId = $(selectedRow).attr("rowId");
	$("#"+gridId+" .grid-row").each(function(){
		$(this).removeClass("selected");
	})
	$(selectedRow).addClass("selected");
}

function filterVisibility(gridId)
{
	var showState = $("#"+gridId+" #grid-filter-field").attr("showFilter");
	if (showState == "false")
	{
		$("#"+gridId+" form").slideToggle("slow");
		$("#"+gridId+" #grid-filter-field").attr("showFilter", "true");
		$(".filter-show-button").attr("src", "asset/img/arrow_down.png");
	}
	else
	{
		$("#"+gridId+" form").slideToggle("slow");
		$("#"+gridId+" #grid-filter-field").attr("showFilter", "false");
		$(".filter-show-button").attr("src", "asset/img/arrow_up.png");
	}
}

function showGridChild(showButton, selectedRow, fieldName, gridId, childContent, colspan)
{
	var rowId = $("#"+selectedRow).attr("rowId");
	var visibilityState = $(showButton).attr("showChild");
	if (visibilityState == "true" )
	{
		$(showButton).attr("showChild", "false");
		$(showButton).attr("src", "asset/img/arrow_down.png");
		$(".grid-child-row").slideToggle("slow");
		$(".grid-child-row").remove();
	}
	else
	{
		$(showButton).attr("showChild", "true");
		$(showButton).attr("src", "asset/img/arrow_up.png");
		$.ajax({
			url: "ajax/ajax-content.php?mod="+childContent,
			data: {fieldName: fieldName, rowId: rowId},
			type: 'post',
			success: function(hasil)
			{
				var childRow = "";
				childRow += "<tr class='grid-child-row'>";
				childRow += "<td colspan='2'>&nbsp;</td>";
				childRow += "<td colspan='"+colspan+"' id='active-child-row'></td>";
				childRow += "</tr>";
				
				$("#"+selectedRow).after(childRow);
				$(".grid-child-row").slideToggle("slow");
				$("#active-child-row").html(hasil);
			}
		})
		
	}
	
}

