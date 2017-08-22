
/* 時間を文字列に変換 */
function formatDate(date){
	if(date == null){
		date = new Date();
	}
	return ("" + date.getFullYear() + ("00" + (date.getMonth() + 1)).slice(-2) + ("00" + date.getDate()).slice(-2) + ("00" + date.getHours()).slice(-2) + ("00" + date.getMinutes()).slice(-2) + ("00" + date.getSeconds()).slice(-2) );
}

/* ハッシュを配列に変換する */
function convHash2Arr(hash){

	var arr = [];

	for(var prop in hash){
		arr.push(hash[prop]);
	}

	return arr;
}

/* 配列をハッシュに変換する. キーとするプロパティ名が必要 */
function convArr2Hash(list, key){

	var hash = {};

	if(list == null){
		return hash;
	}

	for(var i = 0; i < list.length; i++){
		hash[list[i][key]] = list[i];
	}

	return hash;
}
function showOnsDialog(msg){
	document.getElementById("alertDialog-content").innerHTML = msg;
	alertDialog.show();
}
