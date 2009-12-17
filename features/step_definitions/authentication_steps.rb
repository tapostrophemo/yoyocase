Given /^I am logged in with username: "([^\"]*)", password: "([^\"]*)"$/ do |username, password|
  do_login(username, password)
end

When /^I login as "([^\"]*)", "([^\"]*)"$/ do |username, password|
  do_login(username, password)
end

def do_login(username, password)
  visit "/login"
  fill_in "Username", :with => username
  fill_in "Password", :with => password
  click_button("Login")
end
