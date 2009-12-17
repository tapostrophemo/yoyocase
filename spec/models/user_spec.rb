require File.dirname(__FILE__) + '/../spec_helper'

describe User do
  before(:each) do
    @user = Factory.create(:user)
  end

  it "should be valid" do
    @user.should be_valid
  end

  describe "validations" do
    it "should require email" do
      @user.email = ""
      @user.should have(3).error_on(:email) # why three? what is Authlogic really doing?
    end
  end

end
