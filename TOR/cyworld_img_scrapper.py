import requests
from lxml import html

session_requests = requests.session()

login_url = "http://cy.cyworld.com/cyMain"
result = session_requests.get(login_url)

tree = html.fromstring(result.text)


payload = {
  "email" : "rupang87@hotmail.com",
  "passwd " : "none"
}

result = session_requests.post(
  login_url, 
  data = payload, 
  headers = dict(referer=login_url)
)

print("============ LOGIN =============")
print(result.content)

url = "http://cy.cyworld.com/home/21001917"
result = session_requests.get(
  url, 
  headers = dict(referer = url)
)

print("============ CONTENT =============")
print(result.content)

# tree = html.fromstring(result.content)
# timeline_imgs = tree.xpath("//li[@class='timeline_img']")

# print(tree)

