require 'spec_helper'

describe Yoyo do
  before(:each) do
    @valid_attributes = {
      :manufacturer => "value for manufacturer",
      :country => "value for country",
      :model_year => 1,
      :model_name => "value for model_name"
    }
  end

  it "should create a new instance given valid attributes" do
    Yoyo.create!(@valid_attributes)
  end
end
