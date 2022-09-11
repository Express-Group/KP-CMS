$(document).ready(function()
{
	
	$("#monthlyPredictions_form").validate(
	{
		ignore:[],
		debug: false,
		rules:
		{
			txtPredictionDetails:
			{
			  required: function() { CKEDITOR.instances.txtPredictionDetails.updateElement();}
			},
			raasi_name: { required: true},
			prediction_date: { required: true },
			prediction_month: { required: true },
			prediction_start_date : { required: true },
			prediction_end_date: { required: true },
				
		},
		messages: 
		{
			txtPredictionDetails: { required: "Please enter General Predictions",},
			raasi_name: { required: "Please select Raasi Name",},
			prediction_month: { required: "Please select month" },
			prediction_date: { required: "Please select year" },
			prediction_start_date : { required: "Please select Prediction start date" },
			prediction_end_date : { required: "Please select Prediction end date" },
		},
		errorPlacement: function (error, element)
		{
			if(element.attr("name") == 'txtPredictionDetails')
			{ 
				error.insertAfter($("#predictiondetails_error"));
			}			
			else if(element.attr("name") == 'prediction_date')
			{ 
				error.insertAfter($("#date_error1"));
			}
			else if(element.attr("name") == 'prediction_month')
			{
				error.insertAfter($("#date_error"));
				//error.insertAfter($("#"+element.attr("name")));
			}
			else if(element.attr("name") == 'prediction_start_date')
			{
				error.insertAfter($("#prediction_start_date_error"));
				//error.insertAfter($("#"+element.attr("name")));
			}
			else if(element.attr("name") == 'prediction_end_date')
			{
				error.insertAfter($("#prediction_end_date_error"));
				//error.insertAfter($("#"+element.attr("name")));
			}
			else
			{
				error.insertAfter($("#"+element.attr("name")));
			}
		}
	});
	$("#btnMonthlyPrediction").click(function() 
	{
		if($("#monthlyPredictions_form").valid())
		{
			if(base_lang!="tamil"){
			var raasi_id = ($("#raasi_name").val() !='') ? $("#raasi_name").val() : "";
			var prediction_id = ($("#hidden_id").val() !='') ? $("#hidden_id").val() : "";
			var month = $("#prediction_month").val();
			switch(month) {
		case 'January':
			var ClassName = "01";
		break;
		case 'February':
			var ClassName = "02";
		break;
		case 'March':
			var ClassName = "03";
		break;
		case 'April':
			var ClassName = "04";
		break;
		case 'May':
		var ClassName = "05";
		break;
		case 'June':
			var ClassName = "06";
		break;
		case 'July':
			var ClassName = "07";
		break;
		case 'August':
			var ClassName = "08";
		break;
		case 'September':
			var ClassName = "09";
		break;
		case 'October':
			var ClassName = "10";
		break;
		case 'November':
			var ClassName = "11";
		break;
		case 'December':
			var ClassName = "12";
		break;
		case '- Select -':
			//$rasi_name = "";
			var ClassName = "01";
		break;
		default:
			var ClassName = "01";
		break;
	}
	//var str1 = "Hello ";
	var date1= $("#prediction_date").val();
			var date = '01-'+ClassName+'-'+date1;
			//alert(date);
			//var date = $("#prediction_date").val();
			$.ajax({
				type: "POST",
				data: {"raasi_id":raasi_id,"month":month, "schd_date":date, "prediction_id":prediction_id, 'lang': 'english'},
				url: base_url+folder_name+"/astro_monthly_prediction/check_alreadyExists",
				success: function(data)
				{
					//alert(data);
					if(data == "exists" && $("#hidden_id").val()=='')
					{
							$("#already_error").html("Astrology-Monthly Predictions already exists for this Month!");
							return false;
					}
					else
					{
						$("#date_error").html("");
						
						var confirm_msg = $("#hidden_id").val();
						if(confirm_msg !="")
							var confirm_status = confirm("Are you sure you want to update the Astrology-Monthly Predictions?");
						else
							var confirm_status = confirm("Are you sure you want to add the Astrology-Monthly Predictions?");
						if(confirm_status==true)
						{
							$("#monthlyPredictions_form").submit();
						}
						
					}
				}
			});
			
			
			}else
			{
				var raasi_id = ($("#raasi_name").val() !='') ? $("#raasi_name").val() : "";
			    var prediction_id = ($("#hidden_id").val() !='') ? $("#hidden_id").val() : "";
				var prediction_start_date = ($("#prediction_start_date").val() !='') ? $("#prediction_start_date").val() : "";
				var prediction_end_date = ($("#prediction_end_date").val() !='') ? $("#prediction_end_date").val() : "";
			
				$.ajax({
				type: "POST",
				data: {"raasi_id":raasi_id,"prediction_start_date":prediction_start_date, "prediction_end_date":prediction_end_date, "prediction_id":prediction_id, 'lang': 'tamil'},
				url: base_url+folder_name+"/astro_monthly_prediction/check_alreadyExists",
				success: function(data)
				{
					//alert(data);
					if(data == "exists")
					{
						$("#already_error").html("Astrology-Monthly Tamil Predictions already exists for this Month!");
						return false;
					}
					else
					{
						$("#date_error").html("");
						
						var confirm_msg = $("#hidden_id").val();
						if(confirm_msg !="")
							var confirm_status = confirm("Are you sure you want to update the Astrology-Tamil Monthly Predictions?");
						else
							var confirm_status = confirm("Are you sure you want to add the Astrology-Tamil Monthly Predictions?");
						if(confirm_status==true)
						{
							$("#monthlyPredictions_form").submit();
						}
						
					}
				}
			});
			}
		}
	});	
	
});