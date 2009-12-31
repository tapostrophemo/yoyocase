# based on: http://openmonkey.com/articles/2009/03/cucumber-steps-for-testing-page-urls-and-redirects
Then /^I should be redirected to the (.+?) page$/ do |page_name|
  request.headers['HTTP_REFERER'].should_not be_nil
  request.headers['HTTP_REFERER'].should_not == request.request_uri

#  save_and_open_page
#  puts response.headers.to_a.join("\n").to_s

  response.status.should contain("302 Found")
  response.headers['Location'].should contain(path_to(page_name))
end

When /^flickr redirects to my flickr callback path/ do
  visit path_to("flickr callback path")
end
