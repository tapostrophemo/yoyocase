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

And /^I have a yoyo in my collection year: (\d+), manufacturer: "([^\"]*)", name: "([^\"]*)" with photo "([^\"]+)"$/ do |year, make, model, url|
  yoyo = UserSession.find.record.yoyos.create({
    :model_year => year,
    :manufacturer => make,
    :model_name => model})
  yoyo.photos.create({:url => url})
end

And /^I want to add a yoyo$/ do
  Then 'I follow "collection"'
  And 'I follow "Add one?"'
end

When /^I want to update yoyo year: (\d+), manufacturer: "([^\"]*)", name: "([^\"]*)"$/ do |year, make, model|
  Then 'I follow "collection"'
  And "I follow \"#{year} #{make} #{model}\""
  And 'I press "Edit"'
end

Given /^yoyo for user (\d+) has photo "([^\"]*)"$/ do |user_id, photo|
  user = User.find(user_id.to_i)
  user.yoyos[0].photos << Photo.new({:url => photo})
end

Given /^yoyo (\d+) has no photos$/ do |id|
  Photo.delete(:all)
end

And /^I have (\d+) photos? in my photostream$/ do |num_photos|
  class FakePhoto
    def initialize(num); @num = num; end
    def title; "photo #{@num}"; end
    def url(which); "http://somewhere.com/photo_#{@num}_#{which}.jpg"; end
  end

  FAKE_PHOTOS = [] # TODO: is there a better way to do this than w/const? if so, use it
  if num_photos.to_i > 0
    (1..num_photos.to_i).each { |i| FAKE_PHOTOS << FakePhoto.new(i) }
  end

  class ApplicationController < ActionController::Base
    private
    def user_flickr_photos; FAKE_PHOTOS; end
  end
end

Then /^I should see (\d+) thumbnails?$/ do |num|
  (1..num.to_i).each do |i|
    Then "I should see \"photo #{i}\""

    # thanks, http://pivotallabs.com/users/chad/blog/articles/802-gogaruco-09-webrat-rails-acceptance-testing-evolved-bryan-helmkamp
    response.should have_selector("img", :src => "http://somewhere.com/photo_#{i}_thumbnail.jpg")
  end
end

Then /^I should see image "([^\"]*)"$/ do |url|
  response.should have_selector("img", :src => url)
end

Then /^I should not see image "([^\"]*)"$/ do |url|
  response.should_not have_selector("img", :src => url)
end
