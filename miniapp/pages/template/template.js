var tempobj = {
  backpage:function(e){
    var url = e.currentTarget.dataset['url'];
    if(url){
      if(checkurl(url)){
        wx.navigateTo({
          url: url
        })
      }else{
        wx.switchTab({
          url: url
        })
      }
      wx.navigateTo({
        url: url
      })
    }else{
      wx.navigateBack({
        
      })
    }
  }
};
function checkurl(url) {
  if (url.indexOf('/index/') > 0) return false;
  if (url.indexOf('/near_teacher/') > 0) return false;
  if (url.indexOf('/course/') > 0) return false;
  if (url.indexOf('/user_center/') > 0) return false;
  return true;
}
export default tempobj;