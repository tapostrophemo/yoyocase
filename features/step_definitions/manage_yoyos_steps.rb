Given /^I have no yoyos$/ do
  Yoyo.delete(:all)
end

Then /^I should have (\d+) yoyos? in my collection$/ do |num|
  Yoyo.find(:all).size.should == num.to_i
end
