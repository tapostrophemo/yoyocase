def contains(texts)
  texts.each do |t|
    response.should contain t
  end
end

def does_not_contain(texts)
  texts.each do |t|
    response.should_not contain t
  end
end

Given /^I have no users$/ do
  User.delete(:all)
end

Then /^I should be logged in$/ do
  contains ['logout', 'preferences']
  does_not_contain ['login', 'register']
end

Then /^I should not be logged in$/ do
  does_not_contain ['logout', 'preferences']
  contains ['login', 'register']
end

When /^I register with username: "([^\"]*)", password: "([^\"]*)", email: "([^\"]*)"$/ do |username, password, email|
  visit "/register"
  fill_in "Username", :with => username
  fill_in "Password", :with => password
  fill_in "Email", :with => email
  click_button("Register")
end
