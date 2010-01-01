Given /^I have no yoyos$/ do
  Yoyo.delete(:all)
end

Then /^I should have (\d+) yoyos? in my collection$/ do |num|
  Yoyo.find(:all).size.should == num.to_i
end

And /^I have a yoyo in my collection year: (\d+), manufacturer: "([^\"]*)", name: "([^\"]*)"$/ do |year, make, model|
  UserSession.find.record.yoyos.create({
    :model_year => year,
    :manufacturer => make,
    :model_name => model})
end

And /^I want to add a yoyo/ do
  Then 'I follow "collection"'
  And 'I follow "Add one?"'
end

class ApplicationController < ActionController::Base
  private
  def user_flickr_photos
    []
  end
end
