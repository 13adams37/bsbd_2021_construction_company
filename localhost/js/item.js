function action_tbl(count, action_title, action_about, action_price, action_img, action_id)
{
	var t = document.getElementsByClassName("area")[1];
	var s = "";
	var k = Math.ceil(count);
	var at_str = action_title.split(',');
	var ab_str = action_about.split(',');
	var ap_str = action_price.split(',');
	var ai_str = action_img.split(',');
	var ai_id = action_id.split(',');
	
	for (var i = 1; i < k+1; i++)
	{
		console.log(ai_id[i]);
		s += '<div><img src="'+ ai_str[i] +'" width="120" height="120" ><strong>'+ at_str[i] +'</strong><span>'+ ab_str[i] +'</span><em>'+ ap_str[i] +' ₽</em><a class="emm" href="order.php?page=order&action=add&id='+ ai_id[i] + '">Заказать</a></div>';
	}
	t.innerHTML = s;
}
