/**
 * Created by mabuhasna on 6/27/2017.
 */
var addedCategoriesText;
var addedCategoriesIDs;
var addedPhotos;
var tagsText;
var docReady = setInterval(function () {
    if(document.readyState !== "complete"){
        return;
    }
    clearInterval(docReady);
var addCategoryBtn=document.getElementsByClassName('btn')[0];
    var addTagBtn=document.getElementsByClassName('btn')[1];
    tagsText=document.getElementById('tags');
    addedCategoriesIDs =  document.getElementById('categories');
    addCategoryBtn.addEventListener('click',addCategoryToPost);
    addTagBtn.addEventListener('click',addTagToPost);
    addedCategoriesText=document.getElementsByClassName('added-categories')[0];

    for(i=0;i<addedCategoriesText.firstElementChild.children.length;i++){
        addedCategoriesText.firstElementChild.children[i].firstElementChild.addEventListener('click',removeCategoryFromPost);
    }
    addedPhotos=document.getElementsByClassName('added-photos')[0];
    for(j=0;j<addedPhotos.firstElementChild.children.length;j++){
        addedPhotos.firstElementChild.children[j].firstElementChild.children[0].addEventListener('click',removePhotoFromPost);
        addedPhotos.firstElementChild.children[j].firstElementChild.children[1].addEventListener('click',modalOpen);
        addedPhotos.firstElementChild.children[j].firstElementChild.children[1].addEventListener('click',modalContent);
    }
    document.getElementById('modal-close').addEventListener('click',modalClose);
},100);
function removePhotoFromPost(event){
    event.preventDefault();
    // alert('hello');
    var txt;
    var photoId =event.currentTarget.dataset['id'];
    var postId =event.currentTarget.dataset['post_id'];

    var r = confirm("Please Confirm Deleting Process?");
    if (r == true) {
       
        ajax('GET','/admin/blog/post/photo/delete/'+postId+'&'+photoId,null,endPhotoDelete,[event.path[3]]);

    } else {
        alert('Process Canceled');
    }

}
function endPhotoDelete(params,success,responseObj){
var element = params[0];
    if(success){
        var message = responseObj.message;
        alert(message);
        element.style.backgroundColor='#ffc4bc';
        setTimeout(function(){
            element.remove();

        },300);
    }

}
function modalContent(event) {
    event.preventDefault();
    var src = event.currentTarget.dataset['src'];
    var modal = document.getElementsByClassName('modal')[0];
    var img = document.createElement('img');
    img.src=src;
    img.style.width='90%';
    img.style.height='90%';
    modal.style.backgroundColor='#dedddd';

    modal.insertBefore(img,modal.childNodes[0]);
}
function addCategoryToPost(event){
    event.preventDefault();
    var select = document.getElementById('category_select');
    var selectedCategoryId=select.options[select.selectedIndex].value;
    var selectedCategoryName = select.options[select.selectedIndex].text;
    if(addedCategoriesIDs.value.split(',').indexOf(selectedCategoryId) != -1){
        return;
    }
    if(addedCategoriesIDs.value.length > 0){
        addedCategoriesIDs.value=addedCategoriesIDs.value+','+selectedCategoryId;
    }else{
        addedCategoriesIDs.value = selectedCategoryId;
    }
    var newCategoryLi=document.createElement('li');
    var newCategoryLink=document.createElement('a');
    newCategoryLink.href='#';
    newCategoryLink.innerText=selectedCategoryName;
    newCategoryLink.dataset['category_id']=selectedCategoryId;
    newCategoryLink.addEventListener('click',removeCategoryFromPost);
    newCategoryLi.appendChild(newCategoryLink);
    addedCategoriesText.firstElementChild.appendChild(newCategoryLi);

}
function addTagToPost(event){
    event.preventDefault();
    var select = document.getElementById('tag_select');
    var selectedTagId=select.options[select.selectedIndex].value;
    var selectedTagName = select.options[select.selectedIndex].text;
    if(tagsText.value.split(',').indexOf(selectedTagName) != -1){
        return;
    }
    if(tagsText.value.length > 0){
        tagsText.value=tagsText.value+','+selectedTagName;
    }else{
        tagsText.value = selectedTagName;
    }
    // var newCategoryLi=document.createElement('li');
    // var newCategoryLink=document.createElement('a');
    // newCategoryLink.href='#';
    // newCategoryLink.innerText=selectedCategoryName;
    // newCategoryLink.dataset['category_id']=selectedCategoryId;
    // newCategoryLink.addEventListener('click',removeCategoryFromPost);
    // newCategoryLi.appendChild(newCategoryLink);
    // addedCategoriesText.firstElementChild.appendChild(newCategoryLi);

}
function removeCategoryFromPost(event) {
    event.preventDefault();
    event.target.removeEventListener('click',removeCategoryFromPost);
    var categoryId=event.target.dataset['category_id'];
    var categoryIDArray = addedCategoriesIDs.value.split(',');
    var index=categoryIDArray.indexOf(categoryId);
    categoryIDArray.splice(index,1);
    var newCategoriesIDs = categoryIDArray.join(',');
    addedCategoriesIDs.value=newCategoriesIDs;
    event.target.parentElement.remove();
}
function ajax(method,url,params,callback,callbackParams){
    var http;
    if(window.XMLHttpRequest){
        http=new XMLHttpRequest();
    } else {
        http = new ActiveXObject('Microsoft.XMLHTTP')
    }
    http.onreadystatechange=function(){

        if(http.readyState == XMLHttpRequest.DONE){
            if(http.status == 200){
                var obj = JSON.parse(http.responseText);
                callback(callbackParams,true,obj);
            }else if(http.status == 400) {
                alert('Category could not be saved , Please try again');
                callback(callbackParams,false);
            } else{
                var obj = JSON.parse(http.responseText);
                if(obj.message){
                    alert(obj.message);
                }else{
                    alert('Please check the name');
                }
            }
        }
    }
    http.open(method,baseUrl+url,true);
    http.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    http.setRequestHeader('X-Requested-With','XMLHttpRequest');
    http.send(params+"&_token="+token);
}
