# testAPI

1. Use this CURL request to upload the file
```
curl -F "file=@/Users/user/Desktop/text_file.txt"  "http://localhost/api/getBase64"
```
Where 

* **file** property is required before specifing path of the uploaded file

* **localhost** end point

2. Using 
```
curl -X POST -H "Content-Type: application/x-www-form-urlencoded" -d 'name=Test&language=Heb' "http://localhost/api/sayHelloInLanguage"
```
requires Google Translation API which should be placed in ```APIConstants#GOOGLE_API_KEY```
Otherwise, exception will be thrown
```
{
"status":"error",
"msg":"Input your Google API Key into file apiConstants.php"
}
```
