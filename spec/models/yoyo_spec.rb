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

  it "should have no first_photo by default" do
    Yoyo.create!(@valid_attributes).first_photo.should == nil
  end

  it "should retrieve first photo" do
    yoyo = Yoyo.create!(@valid_attributes)
    yoyo.photos << Photo.new({:url => "http://somewhere.com/image.jpg"})

    yoyo.first_photo.should == "http://somewhere.com/image.jpg"
  end
end
