const nums = [20,5,2,4,9,10]
var sorts_arr = [];
var length = nums.length;
 for(var i=0;i<length;i++){
   if(i>0){
     if(nums.indexOf(sorts) != -1){
     nums.splice(nums.indexOf(sorts),1);
     }
   }
   sorts = sort(nums,i);
   sorts_arr.push(sorts);
   console.log(nums);
  }
console.log(sorts_arr);

function sort(array,start){
  let length = array.length;
  var arr = [];
  //let slice_arr = array.slice(start,length);
    var max_arr = Math.max(...array);
    //arr.push(max_arr);
  return max_arr;
}


const price = [8,7,6,5,4,3]
var length = price.length;
var min_arr = Math.min(...price);
var day_buy = price.indexOf(min_arr);
let slice_arr = price.slice(day_buy,length);
var day_sell = Math.max(...slice_arr);
var max_arr = price.indexOf(day_sell);
if(day_buy < day_sell){
console.log('ควรซื่อวันที่'+(day_buy+1)+'ขายวันที่'+(max_arr+1)+'กำไร'+(day_sell-min_arr));
}else{
console.log('NULL');
}