require File.dirname(__FILE__) + '/../spec_helper'

describe User do
  before(:each) do
    @user = Factory.create(:user)
  end

  it "should be valid" do
    @user.should be_valid
  end
end
