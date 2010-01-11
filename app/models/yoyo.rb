class Yoyo < ActiveRecord::Base
  belongs_to :user
  has_many :photos

  validates_presence_of :model_name
end
