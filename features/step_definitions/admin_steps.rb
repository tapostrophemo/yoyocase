Given /^user: (\d+) was created at: "([^\"]*)"$/ do |user_id, datetime|
  user = User.find(user_id.to_i)
  user.created_at = DateTime.parse(datetime)
  user.save
end

Given /^user: (\d+) last logged in at: "([^\"]*)"$/ do |user_id, datetime|
  user = User.find(user_id.to_i)
  user.created_at = DateTime.parse(datetime)
  user.save
end

Then /^I should see the following accounts:$/ do |accounts_table|
  user_time_now = User.new.created_at

  Then %{I should see "Username"}
  Then %{I should see "Registered on"}
  Then %{I should see "Last login"}

  accounts_table.hashes.each do |hash|
    reg_time = hash['Registered on'] == "today" ? user_time_now : hash['Registered on']
    login_time = hash['Last login'] == "today" ? user_time_now : hash['Last login']

    Then %{I should see "#{hash['Username']}"}
    Then %{I should see "#{reg_time}"}
    Then %{I should see "#{login_time}"}
  end
end
