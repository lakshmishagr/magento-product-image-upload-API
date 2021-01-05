# magento-product-image-upload-API
It have Api Codes of magento are :-
1. Product search by url_key
2. Product search by Id
3. Get product sku by url_key
4. Get category id by category url_key
5. Get Best seller list
6. Get also viewed products by product id
7. Upload product Image by product sku


Upload Image to Product :-
  Upload image to product has two options like direct image upload from system and image url. 
  In form data select image as “images” field name , or add image url as “link” name
  Every image has a type property like “image/thumbnail/small_image” that values in an array like mentioned below as field name “types”.
  
  
End Point : https://admin.peakboo.com/rest/V1/api/uploadProductImage/:{sku}
Method : POST
Headers :  Content-Type - multipart/form-data
	
Params :
      Form-data : types = ["image"] / ["thumbnail" ] / ["small_image"]    / ["image","thumbnail","small_image"]
      images = Media File Image ( select from System)
      link = Image Url
 
Response : 200 OK , (Product Details in that  media_gallery_entries shows uploaded image path)

Screenshots : in Screenshots folder
